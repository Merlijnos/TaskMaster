<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Team;
use App\Models\Project;
use App\Models\Task;
use App\Models\Comment;
use App\Models\File;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ModelRelationshipsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_relationships()
    {
        $user = User::factory()->create();
        
        $this->assertTrue(method_exists($user, 'ownedTeams'));
        $this->assertTrue(method_exists($user, 'teams'));
        $this->assertTrue(method_exists($user, 'projects'));
        $this->assertTrue(method_exists($user, 'assignedTasks'));
        $this->assertTrue(method_exists($user, 'createdTasks'));
        $this->assertTrue(method_exists($user, 'comments'));
        $this->assertTrue(method_exists($user, 'files'));
    }

    public function test_project_relationships()
    {
        $project = new Project();
        
        $this->assertTrue(method_exists($project, 'team'));
        $this->assertTrue(method_exists($project, 'user'));
        $this->assertTrue(method_exists($project, 'tasks'));
        $this->assertTrue(method_exists($project, 'comments'));
        $this->assertTrue(method_exists($project, 'files'));
    }

    public function test_task_relationships()
    {
        $task = new Task();
        
        $this->assertTrue(method_exists($task, 'project'));
        $this->assertTrue(method_exists($task, 'assignedUser'));
        $this->assertTrue(method_exists($task, 'creator'));
        $this->assertTrue(method_exists($task, 'comments'));
        $this->assertTrue(method_exists($task, 'files'));
    }

    public function test_team_relationships()
    {
        $team = new Team();
        
        $this->assertTrue(method_exists($team, 'owner'));
        $this->assertTrue(method_exists($team, 'members'));
        $this->assertTrue(method_exists($team, 'projects'));
        $this->assertTrue(method_exists($team, 'admins'));
    }

    public function test_comment_relationships()
    {
        $comment = new Comment();
        
        $this->assertTrue(method_exists($comment, 'user'));
        $this->assertTrue(method_exists($comment, 'commentable'));
    }

    public function test_file_relationships()
    {
        $file = new File();
        
        $this->assertTrue(method_exists($file, 'uploader'));
        $this->assertTrue(method_exists($file, 'fileable'));
        $this->assertTrue(method_exists($file, 'getUrlAttribute'));
    }
} 