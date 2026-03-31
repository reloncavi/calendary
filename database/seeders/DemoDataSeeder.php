<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\EquipmentLoan;
use App\Models\Event;
use App\Models\Meeting;
use App\Models\Venue;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        // ── Venues ───────────────────────────────────────────────────────────
        $venues = [
            ['name' => 'Sala Azul',       'address' => 'Piso 1, Ala Norte'],
            ['name' => 'Sala Verde',      'address' => 'Piso 2, Ala Sur'],
            ['name' => 'Auditorio Central', 'address' => 'Planta Baja, Edificio A'],
            ['name' => 'Sala de Reuniones Ejecutiva', 'address' => 'Piso 3, Ala Oriente'],
            ['name' => 'Laboratorio Multimedia', 'address' => 'Piso 1, Edificio B'],
        ];

        $createdVenues = [];
        foreach ($venues as $v) {
            $createdVenues[] = Venue::create($v);
        }

        // ── Events ───────────────────────────────────────────────────────────
        $now   = now();
        $events = [
            ['name' => 'Capacitación en Primeros Auxilios',  'venue' => 0, 'start' => $now->copy()->subDays(5)->setTime(9, 0),  'end' => $now->copy()->subDays(5)->setTime(13, 0)],
            ['name' => 'Taller de Liderazgo',                'venue' => 1, 'start' => $now->copy()->subDays(3)->setTime(10, 0), 'end' => $now->copy()->subDays(3)->setTime(12, 0)],
            ['name' => 'Jornada de Planificación Anual',     'venue' => 2, 'start' => $now->copy()->subDay()->setTime(8, 30),   'end' => $now->copy()->subDay()->setTime(17, 0)],
            ['name' => 'Seminario de Innovación',            'venue' => 2, 'start' => $now->copy()->addDay()->setTime(9, 0),    'end' => $now->copy()->addDay()->setTime(18, 0)],
            ['name' => 'Reunión de Coordinación Regional',   'venue' => 0, 'start' => $now->copy()->addDays(2)->setTime(14, 0), 'end' => $now->copy()->addDays(2)->setTime(16, 0)],
            ['name' => 'Taller de Comunicaciones',           'venue' => 1, 'start' => $now->copy()->addDays(4)->setTime(10, 0), 'end' => $now->copy()->addDays(4)->setTime(12, 30)],
            ['name' => 'Ceremonia de Premiación',            'venue' => 2, 'start' => $now->copy()->addDays(7)->setTime(18, 0), 'end' => $now->copy()->addDays(7)->setTime(20, 0)],
            ['name' => 'Capacitación Sistema ERP',           'venue' => 4, 'start' => $now->copy()->addDays(10)->setTime(9, 0), 'end' => $now->copy()->addDays(10)->setTime(17, 0)],
        ];

        foreach ($events as $e) {
            Event::create([
                'name'       => $e['name'],
                'venue_id'   => $createdVenues[$e['venue']]->id,
                'start_time' => $e['start']->format('Y-m-d H:i:s'),
                'end_time'   => $e['end']->format('Y-m-d H:i:s'),
            ]);
        }

        // ── Meetings ─────────────────────────────────────────────────────────
        $meetings = [
            ['attendees' => 'Dirección de RR.HH.',       'start' => $now->copy()->subDays(4)->setTime(9, 0),   'end' => $now->copy()->subDays(4)->setTime(10, 0)],
            ['attendees' => 'Comité de Seguridad',        'start' => $now->copy()->subDays(2)->setTime(15, 0),  'end' => $now->copy()->subDays(2)->setTime(16, 0)],
            ['attendees' => 'Equipo de Desarrollo TI',    'start' => $now->copy()->setTime(11, 0),              'end' => $now->copy()->setTime(12, 0)],
            ['attendees' => 'Mesa Directiva',             'start' => $now->copy()->addDays(1)->setTime(10, 0),  'end' => $now->copy()->addDays(1)->setTime(11, 30)],
            ['attendees' => 'Coordinadores de Área',      'start' => $now->copy()->addDays(3)->setTime(9, 30),  'end' => $now->copy()->addDays(3)->setTime(11, 0)],
            ['attendees' => 'Revisión Presupuestaria Q2', 'start' => $now->copy()->addDays(5)->setTime(14, 0),  'end' => $now->copy()->addDays(5)->setTime(15, 30)],
            ['attendees' => 'Equipo de Comunicaciones',   'start' => $now->copy()->addDays(8)->setTime(16, 0),  'end' => $now->copy()->addDays(8)->setTime(17, 0)],
        ];

        foreach ($meetings as $m) {
            Meeting::create([
                'attendees'  => $m['attendees'],
                'start_time' => $m['start']->format('Y-m-d H:i:s'),
                'end_time'   => $m['end']->format('Y-m-d H:i:s'),
            ]);
        }

        // ── Equipment ────────────────────────────────────────────────────────
        $equipment = [
            ['name' => 'Proyector Epson EB-X41',   'type' => 'proyector', 'code' => 'PRY-001'],
            ['name' => 'Proyector BenQ MX808ST',   'type' => 'proyector', 'code' => 'PRY-002'],
            ['name' => 'Proyector Portátil ViewSonic', 'type' => 'proyector', 'code' => 'PRY-003'],
            ['name' => 'Notebook Dell Latitude',   'type' => 'notebook',  'code' => 'NB-001'],
            ['name' => 'Notebook Lenovo ThinkPad', 'type' => 'notebook',  'code' => 'NB-002'],
            ['name' => 'Micrófono Inalámbrico JBL', 'type' => 'micrófono', 'code' => 'MIC-001'],
            ['name' => 'Micrófono de Solapa Sennheiser', 'type' => 'micrófono', 'code' => 'MIC-002'],
            ['name' => 'Pantalla Retráctil 100"',  'type' => 'otro', 'code' => 'SCR-001', 'description' => 'Pantalla de proyección retráctil manual'],
            ['name' => 'Control Presentador Logitech', 'type' => 'otro', 'code' => 'CTRL-001', 'description' => 'Presenter inalámbrico con puntero láser'],
        ];

        $createdEquipment = [];
        foreach ($equipment as $eq) {
            $createdEquipment[] = Equipment::create([
                'name'        => $eq['name'],
                'type'        => $eq['type'],
                'code'        => $eq['code'],
                'description' => $eq['description'] ?? null,
            ]);
        }

        // ── Equipment Loans ───────────────────────────────────────────────────
        $loans = [
            // Returned loans (histórico)
            ['equipment' => 0, 'borrower' => 'Carlos Mendoza', 'purpose' => 'Capacitación RR.HH.',     'start' => $now->copy()->subDays(6)->setTime(8, 0),  'end' => $now->copy()->subDays(6)->setTime(13, 0),  'returned_at' => $now->copy()->subDays(6)->setTime(13, 30)],
            ['equipment' => 3, 'borrower' => 'Ana Torres',     'purpose' => 'Presentación clientes',    'start' => $now->copy()->subDays(4)->setTime(9, 0),  'end' => $now->copy()->subDays(4)->setTime(11, 0),  'returned_at' => $now->copy()->subDays(4)->setTime(11, 15)],
            ['equipment' => 5, 'borrower' => 'Pedro Rojas',    'purpose' => 'Taller de liderazgo',      'start' => $now->copy()->subDays(3)->setTime(10, 0), 'end' => $now->copy()->subDays(3)->setTime(12, 0),  'returned_at' => $now->copy()->subDays(3)->setTime(12, 0)],
            // Active loans (activos ahora)
            ['equipment' => 1, 'borrower' => 'María González', 'purpose' => 'Jornada de planificación', 'start' => $now->copy()->setTime(8, 0),              'end' => $now->copy()->setTime(18, 0),               'returned_at' => null],
            ['equipment' => 4, 'borrower' => 'Luis Herrera',   'purpose' => 'Capacitación ERP',         'start' => $now->copy()->setTime(9, 0),              'end' => $now->copy()->addDays(1)->setTime(17, 0),   'returned_at' => null],
            // Future loans
            ['equipment' => 2, 'borrower' => 'Sofía Martínez', 'purpose' => 'Seminario de innovación',  'start' => $now->copy()->addDay()->setTime(8, 30),   'end' => $now->copy()->addDay()->setTime(18, 30),    'returned_at' => null],
            ['equipment' => 6, 'borrower' => 'Diego Castillo', 'purpose' => 'Ceremonia de premiación',  'start' => $now->copy()->addDays(7)->setTime(17, 0), 'end' => $now->copy()->addDays(7)->setTime(21, 0),   'returned_at' => null],
        ];

        foreach ($loans as $l) {
            EquipmentLoan::create([
                'equipment_id'  => $createdEquipment[$l['equipment']]->id,
                'borrower_name' => $l['borrower'],
                'purpose'       => $l['purpose'],
                'start_time'    => $l['start']->format('Y-m-d H:i:s'),
                'end_time'      => $l['end']->format('Y-m-d H:i:s'),
                'returned_at'   => $l['returned_at'] ? $l['returned_at']->format('Y-m-d H:i:s') : null,
            ]);
        }
    }
}
