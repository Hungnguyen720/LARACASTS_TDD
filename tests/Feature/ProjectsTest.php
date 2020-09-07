<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Project;

class ProjectsTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testAUserCanCreateAProject()
    {
        $this->withoutExceptionHandling();

        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph
        ];

        $this->post('/projects', $attributes)->assertRedirect('/projects');

        $this->assertDatabaseHas('projects', $attributes);

        $this->get('/projects')->assertSee($attributes['description'])->assertSee($attributes['title']);
    }

    public function testAProjectRequiresATitle()
    {
        $project = factory(Project::class)->raw(['title' => '']);
        $this->post('/projects', $project)->assertSessionHasErrors('title');
    }
    public function testAProjectRequiresADescription()
    {
        $project = factory(Project::class)->raw(['description' => '']);

        $this->post('/projects', $project)->assertSessionHasErrors('description');
    }

    public function testAUserCanViewAProject()
    {
        $this->withoutExceptionHandling();
        $project = factory(Project::class)->create();
        $this->get('/projects/' . $project->id)
            ->assertSee($project->title)
            ->assertSee($project->description);
    }
}
