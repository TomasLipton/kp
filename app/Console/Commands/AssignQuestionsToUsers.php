<?php

namespace App\Console\Commands;

use App\Models\Question;
use App\Models\User;
use Illuminate\Console\Command;

class AssignQuestionsToUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:assign-questions-to-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign questions to users based on creation year';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting question assignment...');

        // Find users
        $dashaUser = User::query()->where('email', 'dasha.tselkovikova@gmail.com')->first();
        $tomasUser = User::query()->where('email', 'tomaslipton@gmail.com')->first();

        if (! $dashaUser) {
            $this->warn('User with email dasha.tselkovikova@gmail.com not found.');
        }

        if (! $tomasUser) {
            $this->warn('User with email tomaslipton@gmail.com not found.');
        }

        $currentYear = now()->year;
        $thisYearQuestionsCount = 0;
        $otherQuestionsCount = 0;

        // Assign questions created this year to Dasha
        if ($dashaUser) {
            $thisYearQuestionsCount = Question::query()
                ->whereYear('created_at', $currentYear)
                ->update(['user_id' => $dashaUser->id]);

            $this->info("Assigned {$thisYearQuestionsCount} questions from {$currentYear} to {$dashaUser->email}");
        }

        // Assign the rest to Tomas
        if ($tomasUser) {
            $otherQuestionsCount = Question::query()
                ->whereYear('created_at', '<', $currentYear)
                ->orWhereNull('created_at')
                ->update(['user_id' => $tomasUser->id]);

            $this->info("Assigned {$otherQuestionsCount} questions (before {$currentYear} or without date) to {$tomasUser->email}");
        }

        $this->info('Question assignment completed!');

        return Command::SUCCESS;
    }
}
