<?php

namespace App\Livewire\Profile;

use Livewire\Attributes\Computed;
use Livewire\Component;

class ManageSubscription extends Component
{
    public $subscription = null;

    public $paymentMethod = null;

    public function mount(): void
    {
        $this->loadSubscriptionData();
    }

    public function loadSubscriptionData(): void
    {
        $user = auth()->user();
        $this->subscription = $user->subscription();

        if ($this->subscription && $this->subscription->active()) {
            $this->paymentMethod = $user->defaultPaymentMethod();
        }
    }

    #[Computed]
    public function invoices()
    {
        $user = auth()->user();

        if ($this->subscription && $this->subscription->active()) {
            return $user->invoices()->take(5);
        }

        return [];
    }

    public function cancelSubscription(): void
    {
        $user = auth()->user();
        $subscription = $user->subscription();

        if ($subscription && $subscription->active() && ! $subscription->onGracePeriod()) {
            $subscription->cancel();

            session()->flash('success', __('Twoja subskrypcja została anulowana. Nadal masz dostęp do premium funkcji do końca okresu rozliczeniowego.'));

            $this->loadSubscriptionData();
        }
    }

    public function resumeSubscription(): void
    {
        $user = auth()->user();
        $subscription = $user->subscription();

        if ($subscription && $subscription->onGracePeriod()) {
            $subscription->resume();

            session()->flash('success', __('Twoja subskrypcja została wznowiona!'));

            $this->loadSubscriptionData();
        }
    }

    public function render()
    {
        return view('livewire.profile.manage-subscription');
    }
}
