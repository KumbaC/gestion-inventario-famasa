/**
 * Term Drawer Component
 * 
 * Handles the creation of new terms (categories/tags) via a drawer component
 * and updates the post form without page reload.
 */
window.termDrawer = function (taxonomyName) {
    return {
        isOpen: false,
        isSubmitting: false,
        formData: {
            name: '',
            slug: '',
            description: '',
            parent_term: '',
            taxonomy: taxonomyName,
            post_type: '',
            post_id: ''
        },
        errors: {},

        /**
         * Open the drawer
         */
        openDrawer() {
            this.resetForm();
            this.isOpen = true;

            // Add body class to prevent scrolling when drawer is open
            document.body.classList.add('overflow-hidden');
        },

        /**
         * Reset the form data and errors
         */
        resetForm() {
            this.formData = {
                name: '',
                slug: '',
                description: '',
                parent_term: '',
                taxonomy: taxonomyName,
                post_type: '',
                post_id: ''
            };
            this.errors = {};
        },

        /**
         * Close the drawer
         */
        closeDrawer() {
            this.isOpen = false;

            // Remove body class to allow scrolling again.
            document.body.classList.remove('overflow-hidden');
        },

        setFormDataFromInputs() {
            this.formData.post_id = document?.querySelector('input[name="post_id"]')?.value || '';
            this.formData.post_type = document?.querySelector('input[name="post_type"]')?.value || '';
            this.formData.parent_id = document?.querySelector('input[name="parent_term"]')?.value || '';
        },

        /**
         * Save the term via AJAX
         */
        async saveTerm() {
            if (this.isSubmitting) return;

            // Set form data from inputs.
            this.setFormDataFromInputs();

            // Validate required fields.
            if (!this.formData.name || this.formData.name.trim() === '') {
                this.errors.name = 'Name is required';
                return;
            }

            this.isSubmitting = true;
            this.errors = {};

            try {
                // Get CSRF token - safely with fallback.
                let token = '';
                const tokenMeta = document.querySelector('meta[name="csrf-token"]');
                if (tokenMeta) {
                    token = tokenMeta.getAttribute('content');
                } else {
                    // Try to get from a form if meta tag is not available.
                    const tokenInput = document.querySelector('input[name="_token"]');
                    if (tokenInput) {
                        token = tokenInput.value;
                    } else {
                        throw new Error('CSRF token not found. Please refresh the page and try again.');
                    }
                }

                // Make the API request.
                const response = await fetch(`/api/admin/terms/${taxonomyName}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': token
                    },
                    body: JSON.stringify(this.formData)
                });

                const data = await response.json();

                if (!response.ok) {
                    // Handle validation errors.
                    if (response.status === 422 && data.errors) {
                        this.errors = data.errors;
                        this.isSubmitting = false;
                        return;
                    }

                    // Handle other errors.
                    throw new Error(data.message || 'An error occurred while saving the term');
                }

                // Success - add the new term to the form.
                if (data.term) {
                    const taxonomyDivId = 'taxonomy-' + taxonomyName;

                    // Replace the taxonomy div with the new term data.content.
                    const taxonomyDiv = document.getElementById(taxonomyDivId);
                    if (taxonomyDiv) {
                        taxonomyDiv.innerHTML = data.content;
                    } else {
                        console.warn(`Taxonomy div with ID ${taxonomyDivId} not found.`);
                    }

                    // Mark the term as selected in the post form.
                    const termId = document.getElementById(`term_${data.term.id}`);
                    if (termId) {
                        termId.checked = true;
                    } else {
                        console.warn(`Term with ID term_${data.term.id} not found.`);
                    }

                    // Show success toast notification.
                    if (typeof window.showToast === 'function') {
                        window.showToast('success', 'Success!', data.message || `${this.formData.name} created successfully`);
                    }

                    // Close the drawer.
                    this.closeDrawer();
                } else {
                    throw new Error('Invalid response from server: Term data missing');
                }

            } catch (error) {
                console.error('Error saving term:', error);

                // Show error toast notification.
                if (typeof window.showToast === 'function') {
                    window.showToast('error', 'Error', error.message || 'An error occurred while saving the term');
                }
            } finally {
                this.isSubmitting = false;
            }
        },
    };
}
