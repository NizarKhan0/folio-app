<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Experience;
use App\Models\Message;
use App\Models\Profile;
use App\Models\Project;
use App\Models\Skill;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Mail;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::public')]
#[Title('Nizar Khan — Fullstack PHP Developer')]
class PublicPortfolio extends Component
{
    public string $name = '';

    public string $email = '';

    public string $subject = '';

    public string $message = '';

    public bool $contactSent = false;

    public bool $showAllProjectsModal = false;

    public bool $showProjectDetailModal = false;

    public ?int $selectedProjectId = null;

    public ?int $allProjectsDetailId = null;

    public string $skillCategoryFilter = 'all';

    #[Computed]
    public function profile(): Profile
    {
        return Profile::current();
    }

    /**
     * @return Collection<int, Project>
     */
    #[Computed]
    public function projects(): Collection
    {
        return Project::published()->get();
    }

    #[Computed]
    public function selectedProject(): ?Project
    {
        if ($this->selectedProjectId === null) {
            return null;
        }

        return $this->projects()->firstWhere('id', $this->selectedProjectId);
    }

    public function viewProjectDetails(int $id): void
    {
        $this->selectedProjectId = $id;
        $this->showProjectDetailModal = true;
    }

    #[Computed]
    public function allProjectsDetail(): ?Project
    {
        if ($this->allProjectsDetailId === null) {
            return null;
        }

        return $this->projects()->firstWhere('id', $this->allProjectsDetailId);
    }

    public function openAllProjectsDetail(int $id): void
    {
        $this->allProjectsDetailId = $id;
    }

    public function backToAllProjects(): void
    {
        $this->allProjectsDetailId = null;
    }

    public function updatedShowAllProjectsModal(bool $value): void
    {
        if (! $value) {
            $this->allProjectsDetailId = null;
        }
    }

    /**
     * @return Collection<int, Experience>
     */
    #[Computed]
    public function experiences(): Collection
    {
        return Experience::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    /**
     * @return Collection<int, Skill>
     */
    #[Computed]
    public function skills(): Collection
    {
        return Skill::query()
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
    }

    public function submitContact(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'subject' => ['nullable', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:5000'],
        ]);

        $saved = Message::query()->create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['subject'] !== '' ? $validated['subject'] : null,
            'message' => $validated['message'],
            'is_read' => false,
        ]);

        $rawRecipient = $this->profile()->email;
        $recipient = $rawRecipient !== null
            ? trim((string) preg_replace('/\s*\[at\]\s*/i', '@', $rawRecipient))
            : '';

        if ($recipient !== '' && filter_var($recipient, FILTER_VALIDATE_EMAIL) !== false) {
            rescue(static function () use ($recipient, $saved): void {
                $subjectLine = $saved->subject
                    ? "Portfolio Inquiry: {$saved->subject}"
                    : "New Portfolio Message from {$saved->name}";

                $body = "Name: {$saved->name}\nEmail: {$saved->email}\n\n{$saved->message}";

                Mail::raw($body, static function ($mail) use ($recipient, $subjectLine, $saved): void {
                    $mail->to($recipient)
                        ->replyTo($saved->email, $saved->name)
                        ->subject($subjectLine);
                });
            });
        }

        $this->reset(['name', 'email', 'subject', 'message']);
        $this->contactSent = true;

        Flux::toast(variant: 'success', text: 'Thank you! Your message has been sent.');
    }

    public function render(): View
    {
        return view('livewire.public-portfolio');
    }
}
