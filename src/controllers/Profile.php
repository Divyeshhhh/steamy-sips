<?php

namespace Steamy\Controller;

use Steamy\Core\Controller;
use Steamy\Core\Utility;
use Steamy\Model\User;

class Profile
{
    use Controller;

    public function index(): void
    {
        // if user is not signed in, redirect to login page
        session_regenerate_id();
        if (!array_key_exists('user', $_SESSION) || !isset($_SESSION['user'])) {
            Utility::redirect('login');
        }

        // log out user if logout button clicked
        if (isset($_POST['logout_submit'])) {
            $_SESSION = array();
            Utility::redirect('login');
        }

        // fetch user details from database
        $current_user = new User();

        // display user profile
        $this->view(
            'Profile',
            template_title: 'Profile'
        );
    }
}
