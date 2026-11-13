<?php

declare(strict_types=1);

namespace App\Livewire\Admin;

use App\Models\Message;
use Flux\Flux;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts::app')]
#[Title('Messages')]
class MessageInbox extends Component
{
    public bool $showViewModal = false;

    public bool $showDeleteModal = false;

    public ?int $viewingId = null;

    public string $viewingName = '';

    public string $viewingEmail = '';

    public ?string $viewingSubject = null;

    public string $viewingBody = '';

    public string $viewingDate = '';

    public bool $viewingIsRead = false;

    public ?int $deletingId = null;

    public string $deletingName = '';

    /**
     * @return Collection<int, Message>
     */
    #[Computed]
    public function messages(): Collection
    {
        return Message::query()
            ->orderBy('is_read')
            ->latest()
            ->get();
    }

    #[Computed]
    public function unreadCount(): int
    {
        return $this->messages()->where('is_read', false)->count();
    }

    #[Computed]
    public function totalCount(): int
    {
        return $this->messages()->count();
    }

    public function viewMessage(int $id): void
    {
        $message = Message::query()->findOrFail($id);

        if (! $message->is_read) {
            $message->update(['is_read' => true]);
            unset($this->messages, $this->unreadCount);
        }

        $this->viewingId = $message->id;
        $this->viewingName = $message->name;
        $this->viewingEmail = $message->email;
        $this->viewingSubject = $message->subject;
        $this->viewingBody = $message->message;
        $this->viewingDate = $message->created_at?->format('M d, Y g:i A') ?? '';
        $this->viewingIsRead = true;
        $this->showViewModal = true;
    }

    public function toggleRead(int $id): void
    {
        $message = Message::query()->findOrFail($id);
        $message->update(['is_read' => ! $message->is_read]);

        if ($this->viewingId === $message->id) {
            $this->viewingIsRead = $message->is_read;
        }

        unset($this->messages, $this->unreadCount);

        Flux::toast(
            variant: 'success',
            text: $message->is_read ? 'Message marked as read.' : 'Message marked as unread.',
        );
    }

    public function confirmDelete(int $id): void
    {
        $message = Message::query()->findOrFail($id);

        $this->deletingId = $message->id;
        $this->deletingName = $message->name;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId === null) {
            return;
        }

        Message::query()->findOrFail($this->deletingId)->delete();

        if ($this->viewingId === $this->deletingId) {
            $this->showViewModal = false;
            $this->viewingId = null;
        }

        $this->showDeleteModal = false;
        $this->deletingId = null;
        $this->deletingName = '';
        unset($this->messages, $this->unreadCount, $this->totalCount);

        Flux::toast(variant: 'success', text: 'Message deleted.');
    }

    public function render(): View
    {
        return view('livewire.admin.message-inbox');
    }
}
