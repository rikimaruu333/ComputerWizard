document.addEventListener("DOMContentLoaded", function () {
    // JavaScript to display the message when a file is chosen
    document.getElementById("proofImage").addEventListener("change", function() {
        const fileInput = this;
        const messageElement = document.getElementById("proofImageMessage");

        if (fileInput.files && fileInput.files[0]) {
            messageElement.textContent = "Image attached";
            messageElement.style.display = "inline";  // Show the message
        } else {
            messageElement.textContent = "";
            messageElement.style.display = "none";  // Hide the message if no file is selected
        }
    });

    const modal = document.getElementById('reportProfileModal');
    const openModalBtn = document.getElementById('openReportModalBtn');
    const closeModalBtn = document.getElementById('closeReportModalBtn');
    const reportForm = document.getElementById('reportForm');
    const submitButton = reportForm.querySelector('button[type="submit"]');
    const otherTextarea = reportForm.querySelector('textarea[name="otherReason"]');
    const otherRadio = reportForm.querySelector('input[value="Other"]');
    const radioContainer = reportForm.querySelector('.report-options');

    // Open and close modal logic
    openModalBtn.addEventListener('click', () => modal.style.display = 'flex');
    closeModalBtn.addEventListener('click', () => modal.style.display = 'none');
    window.addEventListener('click', (event) => {
        if (event.target === modal) modal.style.display = 'none';
    });

    // Initially disable submit button and set cursor to no-drop
    disableSubmitButton(true);

    // Event delegation for radio buttons and textarea
    radioContainer.addEventListener('change', handleInputChange);
    otherTextarea.addEventListener('input', handleInputChange);

    function handleInputChange() {
        const selectedRadio = reportForm.querySelector('input[name="reportReason"]:checked');

        // Show or hide textarea based on "other" option
        if (selectedRadio && selectedRadio.value === 'Other') {
            otherTextarea.style.display = 'block';
        } else {
            otherTextarea.style.display = 'none';
            otherTextarea.value = ''; // Clear if "other" is not selected
        }

        // Enable or disable the submit button based on conditions
        checkFormState(selectedRadio);
    }

    function checkFormState(selectedRadio) {
        const isRadioSelected = selectedRadio && selectedRadio.value !== 'Other';
        const isOtherFilled = otherRadio.checked && otherTextarea.value.trim() !== '';

        // Toggle the submit button's disabled state and cursor class
        const isFormValid = isRadioSelected || isOtherFilled;
        disableSubmitButton(!isFormValid);
    }

    function disableSubmitButton(disable) {
        submitButton.disabled = disable;
        submitButton.classList.toggle('active', !disable); // Add active class if enabled
        submitButton.style.cursor = disable ? 'no-drop' : 'pointer'; // Set cursor style explicitly
    }

    // Submit the form via AJAX (including the file)
    reportForm.addEventListener('submit', function (event) {
        event.preventDefault(); // Prevent default form submission

        const selectedRadio = reportForm.querySelector('input[name="reportReason"]:checked');
        const otherReason = otherTextarea.value.trim();

        // Determine reportContent based on the selected radio option
        const reportContent = selectedRadio.value === 'Other' ? 'Other' : selectedRadio.value;
        const reportReason = selectedRadio.value === 'Other' ? otherReason : selectedRadio.parentElement.textContent.trim();
        const reportedUserId = document.getElementById('reportUserId').value; // Assuming this is the reported user ID
        const proofImage = document.getElementById('proofImage').files[0]; // Get the attached image file

        // Prepare FormData for file upload
        const formData = new FormData();
        formData.append('reported_user_id', reportedUserId);
        formData.append('report_content', reportContent);
        formData.append('report_reason', reportReason);
        if (proofImage) {
            formData.append('report_proof', proofImage); // Append the image file
        }

        // Prepare the AJAX request
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'system-report-submission.php', true);

        xhr.onload = function () {
            if (xhr.status === 200) {
                toastr.info('Report submitted successfully'); // Show success toast
                modal.style.display = 'none'; // Close modal on success
            } else {
                toastr.error('Error submitting the report'); // Show error toast
            }
        };

        xhr.send(formData); // Send the FormData to the server
    });
});
