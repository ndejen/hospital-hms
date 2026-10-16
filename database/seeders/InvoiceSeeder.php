<?php

namespace Database\Seeders;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Payment;
use App\Models\Appointment;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class InvoiceSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();
        $completedAppts = Appointment::where('status', 'completed')->get();

        $itemTypes = ['consultation', 'lab_test', 'medicine', 'procedure', 'room_charge'];
        $statuses  = ['paid', 'paid', 'paid', 'partial', 'sent', 'draft'];

        foreach ($completedAppts->take(25) as $i => $appt) {
            $subtotal = 0;
            $items = [];

            // Consultation fee
            $consultFee = rand(50, 200);
            $items[] = ['description' => 'Doctor Consultation Fee', 'item_type' => 'consultation', 'quantity' => 1, 'unit_price' => $consultFee, 'total' => $consultFee];
            $subtotal += $consultFee;

            // Optional lab tests
            if ($faker->boolean(50)) {
                $labFee = rand(20, 100);
                $items[] = ['description' => 'Laboratory Tests', 'item_type' => 'lab_test', 'quantity' => 1, 'unit_price' => $labFee, 'total' => $labFee];
                $subtotal += $labFee;
            }

            // Optional medicines
            if ($faker->boolean(60)) {
                $medFee = rand(10, 80);
                $items[] = ['description' => 'Prescribed Medicines', 'item_type' => 'medicine', 'quantity' => 1, 'unit_price' => $medFee, 'total' => $medFee];
                $subtotal += $medFee;
            }

            $tax      = round($subtotal * 0.05, 2);
            $discount = $faker->boolean(20) ? rand(5, 20) : 0;
            $total    = $subtotal + $tax - $discount;

            $status = $faker->randomElement($statuses);
            $amountPaid = match($status) {
                'paid'    => $total,
                'partial' => round($total * 0.5, 2),
                default   => 0,
            };

            $invoice = Invoice::create([
                'patient_id'     => $appt->patient_id,
                'appointment_id' => $appt->id,
                'invoice_number' => 'INV-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                'invoice_date'   => $appt->appointment_date,
                'due_date'       => $appt->appointment_date->addDays(30),
                'subtotal'       => $subtotal,
                'tax'            => $tax,
                'discount'       => $discount,
                'total'          => $total,
                'amount_paid'    => $amountPaid,
                'balance_due'    => $total - $amountPaid,
                'status'         => $status,
            ]);

            foreach ($items as $item) {
                InvoiceItem::create(array_merge($item, ['invoice_id' => $invoice->id]));
            }

            // Add payment for paid/partial
            if ($amountPaid > 0) {
                Payment::create([
                    'invoice_id'     => $invoice->id,
                    'patient_id'     => $appt->patient_id,
                    'payment_number' => 'PAY-' . str_pad($i + 1, 6, '0', STR_PAD_LEFT),
                    'amount'         => $amountPaid,
                    'method'         => $faker->randomElement(['cash', 'credit_card', 'debit_card']),
                    'payment_date'   => now()->subDays(rand(1, 30)),
                    'notes'          => null,
                ]);
            }
        }
    }
}
