document.addEventListener('DOMContentLoaded', function () {
    const contactForm = document.getElementById('basic-contact-form');
    if (contactForm) {
        contactForm.addEventListener('submit', async function (e) {
            e.preventDefault();
            const formData = new FormData(this);
            formData.append('action', 'contact');
            formData.append('nonce', bcf.contactNonce);
            const response = await fetch(bcf.ajax_url, {
                method: 'POST',
                body: formData
            });
            const resultDiv = document.getElementById('basic-contact-result');
            const data = await response.json();
            if (data.success) {
                resultDiv.innerHTML = '<p>' + data.data + '</p>';
                this.reset();
            } else {
                resultDiv.innerHTML = '<p>' + data.data + '</p>';
            }
        });
    }
});