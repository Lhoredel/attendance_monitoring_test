<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\SchoolClass;
use App\Models\Student;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_admin_student_page_renders_and_student_creation_redirects(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->get(route('admin.students.index'))
            ->assertOk();

        $this->actingAs($admin)
            ->post(route('admin.students.store'), [
                'student_number' => 'S-1001',
                'first_name' => 'Avery',
                'last_name' => 'Taylor',
                'grade_level' => '7',
                'section' => 'A',
            ])
            ->assertRedirect(route('admin.students.index'));
    }

    public function test_teacher_dashboard_renders(): void
    {
        /** @var User $teacher */
        $teacher = User::factory()->create(['role' => 'teacher']);

        $this->actingAs($teacher)
            ->get(route('teacher.dashboard'))
            ->assertOk();
    }

    public function test_admin_can_create_a_class_and_save_attendance(): void
    {
        /** @var User $admin */
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.classes.store'), [
                'class_name' => 'Room 12',
                'grade_level' => '7',
                'section' => 'A',
            ])
            ->assertRedirect(route('admin.classes.index'));

        $class = SchoolClass::firstOrFail();
        $student = Student::create([
            'student_number' => 'S-1002',
            'first_name' => 'Jordan',
            'last_name' => 'Lee',
            'grade_level' => '7',
            'section' => 'A',
        ]);
        $class->students()->attach($student);

        $date = today()->toDateString();

        $this->actingAs($admin)
            ->post(route('admin.attendance.store'), [
                'school_class_id' => $class->id,
                'attendance_date' => $date,
                'attendance' => [
                    $student->id => ['status' => 'present'],
                ],
            ])
            ->assertRedirect(route('admin.attendance.index', [
                'school_class_id' => $class->id,
                'attendance_date' => $date,
            ]));

        $this->assertDatabaseHas('attendances', [
            'student_id' => $student->id,
            'school_class_id' => $class->id,
            'status' => 'present',
        ]);
    }
}
