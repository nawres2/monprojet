function toggleForm(formType) {
    if (formType === 'signup') {
        document.getElementById('signin-form').style.display = 'none';
        document.getElementById('signup-form').style.display = 'block';
    } else {
        document.getElementById('signup-form').style.display = 'none';
        document.getElementById('signin-form').style.display = 'block';
    }
}