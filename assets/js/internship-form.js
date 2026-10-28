document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('internshipForm');
    const successMsg = document.getElementById('successMessage');
    const internshipArea = document.getElementById('internshipArea');
    const otherAreaRow = document.getElementById('otherAreaRow');
    const otherArea = document.getElementById('otherArea');
    const duration = document.getElementById('duration');
    const otherDurationRow = document.getElementById('otherDurationRow');
    const otherDuration = document.getElementById('otherDuration');
    const validationAlert = document.getElementById('validationAlert');

    if (internshipArea) {
        internshipArea.addEventListener('change', function() {
            if (this.value === 'Other') {
                otherAreaRow.style.display = 'block';
                otherArea.required = true;
            } else {
                otherAreaRow.style.display = 'none';
                otherArea.required = false;
                otherArea.value = '';
            }
        });
    }

    if (duration) {
        duration.addEventListener('change', function() {
            if (this.value === 'Other') {
                otherDurationRow.style.display = 'block';
                otherDuration.required = true;
            } else {
                otherDurationRow.style.display = 'none';
                otherDuration.required = false;
                otherDuration.value = '';
            }
        });
    }

    function showValidationAlert(message) {
        validationAlert.innerHTML = `<div class="alert-content"><strong>⚠ Validation Error</strong><p>${message}</p></div>`;
        validationAlert.style.display = 'block';
        validationAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        setTimeout(() => {
            validationAlert.style.display = 'none';
        }, 5000);
    }

    function validateForm() {
        const fullName = document.getElementById('fullName').value.trim();
        const dob = document.getElementById('dob').value;
        const email = document.getElementById('email').value.trim();
        const whatsapp = document.getElementById('whatsapp').value.trim();
        const address = document.getElementById('address').value.trim();
        const qualification = document.getElementById('qualification').value;
        const college = document.getElementById('college').value.trim();
        const specialization = document.getElementById('specialization').value.trim();
        const internshipAreaVal = document.getElementById('internshipArea').value;
        const durationVal = document.getElementById('duration').value;

        if (!fullName) {
            showValidationAlert('Please enter your full name');
            return false;
        }
        if (fullName.length < 3) {
            showValidationAlert('Full name must be at least 3 characters long');
            return false;
        }

        if (!dob) {
            showValidationAlert('Please select your date of birth');
            return false;
        }
        const dobDate = new Date(dob);
        const today = new Date();
        const age = today.getFullYear() - dobDate.getFullYear();
        if (age < 16) {
            showValidationAlert('You must be at least 16 years old to apply');
            return false;
        }

        if (!email) {
            showValidationAlert('Please enter your email address');
            return false;
        }
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            showValidationAlert('Please enter a valid email address');
            return false;
        }

        if (!whatsapp) {
            showValidationAlert('Please enter your WhatsApp number');
            return false;
        }
        const phoneRegex = /^[0-9+\-\s()]{10,}$/;
        if (!phoneRegex.test(whatsapp)) {
            showValidationAlert('Please enter a valid WhatsApp number (at least 10 digits)');
            return false;
        }

        if (!address) {
            showValidationAlert('Please enter your address');
            return false;
        }
        if (address.length < 10) {
            showValidationAlert('Address must be at least 10 characters long');
            return false;
        }

        if (!qualification) {
            showValidationAlert('Please select your highest qualification');
            return false;
        }

        if (!college) {
            showValidationAlert('Please enter your college/institute name');
            return false;
        }
        if (college.length < 3) {
            showValidationAlert('College name must be at least 3 characters long');
            return false;
        }

        if (!specialization) {
            showValidationAlert('Please enter your UG/PG specialization');
            return false;
        }
        if (specialization.length < 3) {
            showValidationAlert('Specialization must be at least 3 characters long');
            return false;
        }

        if (!internshipAreaVal) {
            showValidationAlert('Please select an internship area');
            return false;
        }

        if (internshipAreaVal === 'Other') {
            if (!otherArea.value.trim()) {
                showValidationAlert('Please specify your internship area');
                return false;
            }
            if (otherArea.value.trim().length < 3) {
                showValidationAlert('Internship area must be at least 3 characters long');
                return false;
            }
        }

        if (!durationVal) {
            showValidationAlert('Please select internship duration');
            return false;
        }

        if (durationVal === 'Other') {
            if (!otherDuration.value.trim()) {
                showValidationAlert('Please specify the internship duration');
                return false;
            }
            const durationNum = parseInt(otherDuration.value.trim());
            if (isNaN(durationNum) || durationNum < 1 || durationNum > 24) {
                showValidationAlert('Duration must be a number between 1 and 24 months');
                return false;
            }
        }

        return true;
    }

    if (form) {
        form.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!validateForm()) {
                return;
            }

            const internshipAreaVal = document.getElementById('internshipArea').value;
            const finalArea = internshipAreaVal === 'Other' ? document.getElementById('otherArea').value : internshipAreaVal;

            const durationVal = document.getElementById('duration').value;
            const finalDuration = durationVal === 'Other' ? document.getElementById('otherDuration').value + ' months' : durationVal;

            const formData = {
                fullName: document.getElementById('fullName').value,
                dob: document.getElementById('dob').value,
                email: document.getElementById('email').value,
                whatsapp: document.getElementById('whatsapp').value,
                address: document.getElementById('address').value,
                qualification: document.getElementById('qualification').value,
                college: document.getElementById('college').value,
                specialization: document.getElementById('specialization').value,
                internshipArea: finalArea,
                duration: finalDuration
            };

            try {
                const response = await fetch('https://c2ceducation.github.io/c2c.business/send-internship-email.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(formData)
                });

                const result = await response.json();

                if (result.success) {
                    form.style.display = 'none';
                    validationAlert.style.display = 'none';
                    successMsg.style.display = 'block';
                    setTimeout(() => {
                        form.reset();
                        form.style.display = 'flex';
                        successMsg.style.display = 'none';
                        otherAreaRow.style.display = 'none';
                        otherDurationRow.style.display = 'none';
                    }, 5000);
                } else {
                    showValidationAlert('Error: ' + result.message);
                }
            } catch (error) {
                showValidationAlert('An error occurred while submitting the form. Please try again.');
            }
        });
    }
});
