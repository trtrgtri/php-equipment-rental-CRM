<?php

class HomeController
{
    public function index(): void
    {
        if (is_logged_in()) {
            redirect('/dashboard');
        }

        render('home/index', ['title' => 'Equipment Rental CRM']);
    }
}
