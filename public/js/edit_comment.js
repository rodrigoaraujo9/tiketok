function toggleEditForm(commentId) {
    var contentElement = document.getElementById('comment-' + commentId);
    var editForm = document.getElementById('edit-form-' + commentId);

    if (editForm) {
        if (editForm.style.display === 'none' || editForm.style.display === '') {
            editForm.style.display = 'block'; // Show the edit form
            contentElement.style.display = 'none'; // Hide the current content
        } else {
            editForm.style.display = 'none'; // Hide the edit form
            contentElement.style.display = 'block'; // Show the current content
        }
    }
}
