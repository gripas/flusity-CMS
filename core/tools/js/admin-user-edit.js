

function loadUserEditForm(userId) {
  const url = 'get_user.php?user_id=' + userId;
  fetch(url)
      .then(response => response.text())
      .then(html => {
          document.querySelector('#get-user-edit').innerHTML = html;
          initializeEditUserForm();
      })
      .catch(error => {
          console.error('Error loading user edit form:', error);
      });
}

function initializeEditUserForm() {
  const editUserForm = document.querySelector('#edit-user-form');
  if (editUserForm) {
      editUserForm.addEventListener('submit', function (event) {
          event.preventDefault();

          const formData = new FormData(event.target);

          fetch('update_user.php', {
              method: 'POST',
              body: formData
          })
          .then(response => response.json())
          .then(data => {
              if (data.success) {
                  window.location.href = 'users.php';
              } else {
                  alert('Error updating user.');
              }
          })
          .catch(error => {
              console.error('Klaida:', error);
              alert('Error updating user.');
          });
      });
  }

  const cancelEditUserBtn = document.querySelector('#cancel-edit-user');
  if (cancelEditUserBtn) {
      cancelEditUserBtn.addEventListener('click', function () {
          window.location.href = 'users.php';
      });
  }
}

