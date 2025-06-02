document.addEventListener("livewire:initialized", () => {
    const form = $("#adminForm");

    const validator = form.validate({
        rules: {
            name: {
                required: true
            },
            email: {
                required: true,
                email: true
            },
            password: {
                required: function(element) {
                    return {{ $adminId ? 'false' : 'true' }};
                },
                minlength: 6
            },
            status: {
                required: true
            },
            'permission[]': {
                required: true
            }
        },
        messages: {
            name: "Please enter a name",
            email: {
                required: "Please enter an email address",
                email: "Please enter a valid email address"
            },
            password: {
                required: "Please enter a password",
                minlength: "Password must be at least 6 characters long"
            },
            status: "Please select a status",
            'permission[]': "Please select at least one permission"
        },
        submitHandler: function(e) {
            e.preventDefault();
            alert(1);
            console.log('jQuery Validation Passed');
            //@this.call('submitForm');
        }
    });

    $(document).on('submit', "#adminForm", function(e) {

        console.log('Form Submission Intercepted');
        e.preventDefault()
        if (!$(this).valid()) {
            console.log('Validation Failed');
            return false;
        }
    });
});