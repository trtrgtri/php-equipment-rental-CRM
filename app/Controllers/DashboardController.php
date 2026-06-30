<?php

class DashboardController
{
    public function __construct(
        private RenterRepository $renterRepository,
        private RentalService $rentalService
    ) {
    }

    public function index(): void
    {
        require_login();

        $stats = $this->rentalService->getDashboardStats();

        render('dashboard/index', [
            'title' => 'Dashboard',
            'totalRenters' => $this->renterRepository->countAll(''),
            'newRenters' => $this->renterRepository->countByStatus('new'),
            'rentalCounts' => $stats['rental_counts'],
            'totalRevenue' => $stats['total_revenue'],
        ]);
    }
}
