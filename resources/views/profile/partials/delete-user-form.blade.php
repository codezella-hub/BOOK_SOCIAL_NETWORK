<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Delete Account') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <button type="button" id="delete-account-btn" class="bg-red-600 text-white px-4 py-2 rounded-md flex items-center gap-2">
        <i class="fas fa-trash"></i>
        {{ __('Delete Account') }}
    </button>

    <!-- Modal -->
    <div id="delete-account-modal" class="modal" style="display: none;">
        <div class="modal-content">
            <div class="modal-header">
                <h2>{{ __('Are you sure you want to delete your account?') }}</h2>
                <span class="close">&times;</span>
            </div>
            <div class="modal-body">
                <p class="text-sm text-gray-600">
                    {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
                </p>

                <form method="post" action="{{ route('profile.destroy') }}" class="mt-6">
                    @csrf
                    @method('delete')

                    <div class="form-group">
                        <label for="password" class="block font-medium text-sm text-gray-700">
                            {{ __('Password') }}
                        </label>
                        <input id="password" name="password" type="password" class="mt-1 block w-full" placeholder="{{ __('Password') }}" />
                        @if ($errors->userDeletion->get('password'))
                            <div class="text-red-600 text-sm mt-2">
                                @foreach ($errors->userDeletion->get('password') as $error)
                                    <p>{{ $error }}</p>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn-secondary" id="cancel-delete">
                            {{ __('Cancel') }}
                        </button>
                        <button type="submit" class="btn-danger">
                            <i class="fas fa-trash"></i>
                            {{ __('Delete Account') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteBtn = document.getElementById('delete-account-btn');
        const modal = document.getElementById('delete-account-modal');
        const closeBtn = modal.querySelector('.close');
        const cancelBtn = document.getElementById('cancel-delete');

        if (deleteBtn && modal) {
            deleteBtn.addEventListener('click', function() {
                modal.style.display = 'block';
                document.body.style.overflow = 'hidden';
            });

            closeBtn.addEventListener('click', function() {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });

            cancelBtn.addEventListener('click', function() {
                modal.style.display = 'none';
                document.body.style.overflow = 'auto';
            });

            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });

            // Fermer avec la touche Échap
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && modal.style.display === 'block') {
                    modal.style.display = 'none';
                    document.body.style.overflow = 'auto';
                }
            });
        }
    });
</script>
