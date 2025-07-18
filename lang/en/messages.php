<?php

return [

    // Buttons
    'submit' => 'Submit',
    'cancel' => 'Cancel',
    'delete' => 'Delete',
    'forgot_password' => 'Forgot Password',
    'reset_password' => 'Reset Password',
    'send' => 'Send',
    'login' => 'Login',

    // LRF
    'forgot_password' => 'Forgot Password',
    'reset_password' => 'Reset Password',
    'password' => 'Password',
    'enter_password' => 'Enter your password',
    'confirm_password' => 'Confirm Password',
    'email_address' => 'Email Address',
    'enter_email_address' => 'Enter your email address',
    'reset_password_button' => 'Send Reset Password Link',
    'back_to_login' => 'Back to Login',
    'change_password' => 'Change Password',
    'enter_new_password' => 'Enter your new password',
    'enter_current_password' => 'Enter your current password',
    'enter_confirm_password' => 'Enter new password',
    'current_password' => 'Current Password',
    'confirm_new_password' => 'Confirm New Password',
    'new_password' => 'New Password',

    // Dashboard
    'ecommerce' => 'Ecommerce',

    // Vendors
    'vendor' => [
            'create_success' => 'Vendor created successfully.',
            'create_error' => 'Failed to create vendor. Please try again.',
            'retrieve_success' => 'Vendors retrieved successfully.',
            'retrieve_error' => 'Failed to retrieve vendors. Please try again.',
            'show_error' => 'Vendor show endpoint not implemented.',
            'show_debug' => 'Debug mode: show endpoint not available.',
            'edit_success' => 'Vendor data retrieved for editing successfully.',
            'edit_error' => 'Failed to retrieve vendor for editing. Please try again.',
            'update_success' => 'Vendor updated successfully.',
            'update_error' => 'Failed to update vendor. Please try again.',
            'update_invalid_approve_status' => 'Cannot update vendor with invalid approval status.',
            'delete_success' => 'Vendor deleted successfully.',
            'delete_error' => 'Failed to delete vendor. Please try again.',
            'status_success' => 'Vendor status updated successfully.',
            'status_error' => 'Failed to update vendor status. Please try again.',
            'status_cannot_update_approved' => 'Cannot update status for an already approved vendor.',
            'action_success' => 'Vendor action updated successfully.',
            'action_error' => 'Failed to update vendor action. Please try again.',
        ],

   'permission' => [
            'create_success' => 'Permission created successfully.',
            'create_error' => 'Failed to create permission. Please try again.',
            'show_success' => 'Permission retrieved successfully.',
            'show_error' => 'Failed to retrieve permission. Please try again.',
            'update_success' => 'Permission updated successfully.',
            'update_error' => 'Failed to update permission. Please try again.',
            'delete_success' => 'Permission deleted successfully.',
            'delete_error' => 'Failed to delete permission. Please try again.',
            'delete_already_assigned' => 'Cannot delete permission as it is assigned to one or more admins.',
        ],
        'auth' => [
        'login_success' => 'You have successfully logged in.',
        'invalid_credentials' => 'The provided email or password is incorrect.',
        'login_failed' => 'Login attempt failed. Please try again later.',
        'logout_success' => 'You have been logged out successfully.',
        'logout_failed' => 'Logout failed. Please try again.',
        'email_not_found' => 'No account found with this email address.',
        'reset_password_link_sent' => 'A password reset link has been sent to your email.',
        'forgot_password_failed' => 'Unable to process password reset request. Please try again.',
        'password_reset_success' => 'Your password has been reset successfully.',
        'invalid_token' => 'The reset token is invalid or has expired.',
        'password_reset_failed' => 'Password reset failed. Please try again.',
    ],
        'domain' => [
        'created' => 'Domain created successfully.',
        'create_failed' => 'Failed to create domain. Please try again.',
        'updated' => 'Domain updated successfully.',
        'update_failed' => 'Failed to update domain. Please try again.',
        'deleted' => 'Domain deleted successfully.',
        'delete_failed' => 'Failed to delete domain. Please try again.',
    ],
      'product' => [
            'step_one_saved' => 'Product information saved successfully.',
            'error_step_one' => 'Something went wrong while saving product step one.',
            'step_two_saved' => 'Sub-products information saved successfully.',
            'error_step_two' => 'Something went wrong while saving product step two.',
            'error_search_vendor' => 'Something went wrong while searching vendors.',
            'action_updated'=>'Product Updated',
            'error_update_action'=>'Product Not Updated',
        'deleted' => 'Product deleted successfully.',
        'delete_failed' => 'Failed to delete product.',
        ],
         'profile' => [
            'password_change_success' => 'Password changed successfully.',
            'password_change_error' => 'Failed to change password. Please try again.',
        ],
        'sidebar' => [
            'ecommerce' => 'Ecommerce',
            'dashboard' => 'Dashboard',
            'admin' => 'Admin',
            'permissions' => 'Permissions',
            'vendors' => 'Vendors',
            'domains' => 'Domains',
            'team' => 'Team',
            'copyright' => '© 2025 App',
        ],
         'header' => [
            'change_password' => 'Change Password',
            'logout' => 'Logout',
        ],
         'admin_list' => [
            'dashboard' => 'Dashboard',
            'admin_list' => 'Admin List',
            'admin_management' => 'Admin Management',
            'per_page' => 'Per Page',
            'search_placeholder' => 'Search admins...',
            'status' => 'Status',
            'status_all' => 'All',
            'status_active' => 'Active',
            'status_inactive' => 'Inactive',
            'role' => 'Role',
            'role_all' => 'All',
            'actions' => 'Actions',
            'action_select' => 'Select Action',
            'action_activate' => 'Activate Selected',
            'action_deactivate' => 'Deactivate Selected',
            'action_delete' => 'Delete Selected',
            'create_admin' => 'Create Admin',
            'table_select' => 'Select',
            'table_name' => 'Name',
            'table_email' => 'Email',
            'table_role' => 'Role',
            'table_status' => 'Status',
            'table_actions' => 'Actions',
            'no_data' => 'No data available right now',
        ],
        'admin' => [
            'create_success' => 'Admin user created successfully.',
            'create_error' => 'Failed to create admin user. Please try again.',
            'edit_error' => 'Admin user not found. Please try again.',
            'update_success' => 'Admin user updated successfully.',
            'update_error' => 'Failed to update admin user. Please try again.',
            'activate_success' => 'Admins activated successfully.',
            'activate_error' => 'Failed to activate admins. Please try again.',
            'deactivate_success' => 'Admins deactivated successfully.',
            'deactivate_error' => 'Failed to deactivate admins. Please try again.',
            'delete_selected_success' => 'Admins deleted successfully.',
            'delete_selected_error' => 'Failed to delete selected admins. Please try again.',
            'delete_success' => 'Admin deleted successfully.',
            'delete_error' => 'Failed to delete admin. Please try again.',
            'delete_super_admin_denied' => 'Super admin cannot be deleted.',
            'created'       => 'Admin created successfully.',
            'updated'       => 'Admin updated successfully.',
            'not_found'     => 'User not found.',
            'update_error'  => 'Error while updating admin',
            'activated'            => 'Admins activated successfully.',
            'deactivated'          => 'Admins deactivated successfully.',
            'deleted_bulk'         => 'Admins deleted successfully.',
            'deleted'              => 'Admin deleted successfully.',
            'super_admin_delete'   => "Super admin can't be deleted.",
        ],
        'general' => [
            'error_try_again' => 'Something went wrong. Please try again.',
        ],
            'admin_form' => [
            'create_admin' => 'Create Admin',
            'edit_admin' => 'Edit Admin',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'status' => 'Status',
            'select_status' => 'Select Status',
            'no_permissions' => 'No permissions available',
            'next' => 'Next',
            'back' => 'Back',
        ],
            'domain_form' => [
            'dashboard' => 'Dashboard',
            'domain_form' => 'Domain Form',
            'domain_management' => 'Domain Management',
            'per_page' => 'Per Page',
            'search_placeholder' => 'Search Domains...',
            'create_domain' => 'Create Domain',
            'table_name' => 'Name',
            'table_url' => 'URL',
            'table_actions' => 'Actions',
            'no_domains' => 'No domains found.',
            'create_modal_title' => 'Create Domain',
            'edit_modal_title' => 'Edit Domain',
            'name_label' => 'Name',
            'url_label' => 'URL',
            'cancel' => 'Cancel',
            'delete' => [
                'title' => 'Are you sure?',
                'text' => 'You won\'t be able to revert this!',
                'confirm' => 'Yes, delete it!',
            ],
        ],
'permissions' => [
    'title' => 'Permission Management',
    'list' => 'Permission List',
    'add_permission' => 'Add Permission',
    'create_permission' => 'Create Permission',
    'edit_permission' => 'Edit Permission',
    'module' => 'Module',
    'name' => 'Name',
    'slug' => 'Slug',
    'actions' => 'Actions',
    'cancel' => 'Cancel',
    'save' => 'Save',
    'delete_confirm_title' => 'Are you sure?',
    'delete_confirm_text' => 'You won\'t be able to revert this!',
    'delete_confirm_button' => 'Yes, delete it!',
    'delete_cancel_button' => 'Cancel',
    'create_success' => 'Permission created successfully!',
    'update_success' => 'Permission updated successfully!',
    'delete_success' => 'Permission deleted successfully!',
    'error' => 'An error occurred.',
],
    'products' => [
        'add_product' => 'Add Product',
        'edit_product' => 'Edit Product',
        'add_new_product' => 'Add New Product',
        'vendor_list' => 'Vendor List',
        'create_description' => 'Create a new product by filling in the details in this multi-step form.',
        'update_description' => 'Update the product details in this multi-step form.',
        'step_1' => 'Product Info',
        'step_2' => 'Sub Products',
        'step_3' => 'Add On Items',
        'basic_information' => 'Basic Information',
        'product_name' => 'Product Name',
        'description' => 'Description',
        'status' => 'Status',
        'add_ons_extras' => 'Add-ons & Extras',
        'add_option' => 'Add Option',
        'option_name' => 'Option Name',
        'price' => 'Price',
        'select_type' => 'Select Type',
        'product_variant' => 'Product Variant',
        'size_type' => 'Size Type',
        'size' => 'Size',
        'base_price' => 'Base Price',
        'quantity' => 'Quantity',
        'product_images' => 'Product Images',
        'upload_images' => 'Upload Images',
        'no_images_added' => 'No images added yet',
        'specifications' => 'Specifications',
        'specification' => 'Specification',
        'calories' => 'Calories',
        'high_calories' => 'High calories',
        'previous' => 'Previous',
        'save_continue' => 'Save & Continue',
        'save_finish' => 'Save & Finish',
        'submit_product' => 'Submit Product',
        'validation' => [
            'name_required' => 'Please enter a product name',
            'name_minlength' => 'Product name must be at least 3 characters long',
            'description_required' => 'Please enter a product description',
            'description_minlength' => 'Product description must be at least 10 characters long',
            'type_required' => 'Please select a type.',
            'option_name_required' => 'Please enter a name.',
            'price_required' => 'Please enter a price.',
            'size_type_required' => 'Please select a size type.',
            'size_type_maxlength' => 'Size type cannot exceed 50 characters.',
            'size_required' => 'Please enter a size.',
            'size_maxlength' => 'Size cannot exceed 50 characters.',
            'price_number' => 'Price must be a valid number.',
            'price_min' => 'Price cannot be negative.',
            'base_price_required' => 'Please enter a base price.',
            'base_price_number' => 'Base price must be a valid number.',
            'base_price_min' => 'Base price cannot be negative.',
            'quantity_required' => 'Please enter a quantity.',
            'quantity_digits' => 'Quantity must be a whole number.',
            'quantity_min' => 'Quantity cannot be negative.',
            'status_required' => 'Please select a status.',
            'spec_name_required' => 'Please enter a specification name.',
            'spec_name_maxlength' => 'Specification name cannot exceed 100 characters.',
            'spec_value_required' => 'Please enter a specification value.',
            'spec_value_maxlength' => 'Specification value cannot exceed 100 characters.',
            'image_required' => 'At least one image is required',
            'max_images' => 'You can add a maximum of 5 images.',
            'fill_required_fields' => 'Please fill in all required fields and upload at least one image before adding a new variant.',
            'fill_spec_fields' => 'Please fill in all specification fields.',
        ],
        'success' => [
            'sub_products_saved' => 'Sub-products saved successfully!',
            'product_created' => 'Product created successfully',
        ],
        'error' => 'An error occurred while saving.',
    ],
];
