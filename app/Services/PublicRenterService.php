<?php

class PublicRenterService
{
    private const RATE_LIMIT_SECONDS = 5;

    public function __construct(private RenterRepository $repo)
    {
    }

    public function store(array $input): array
    {
        if (!$this->passesAntiSpam($input)) {
            return [
                'success' => false,
                'errors' => ['general' => 'Yêu cầu không hợp lệ hoặc gửi quá nhanh. Vui lòng thử lại sau.'],
                'values' => $this->extractValues($input),
            ];
        }

        $validation = $this->validatePublicData($input);

        if (!empty($validation['errors'])) {
            return ['success' => false, 'errors' => $validation['errors'], 'values' => $validation['values']];
        }

        try {
            $this->repo->create($validation['values']);
            $_SESSION['public_renter_last_submit'] = time();

            return ['success' => true, 'errors' => []];
        } catch (DuplicateRecordException) {
            return [
                'success' => false,
                'errors' => ['email' => 'Email này đã được đăng ký trước đó.'],
                'values' => $validation['values'],
            ];
        } catch (Throwable $e) {
            return [
                'success' => false,
                'errors' => ['general' => safe_db_error_message($e)],
                'values' => $validation['values'],
            ];
        }
    }

    private function passesAntiSpam(array $input): bool
    {
        $honeypot = trim($input['website'] ?? '');

        if ($honeypot !== '') {
            log_error('Public renter form blocked by honeypot.');

            return false;
        }

        $lastSubmit = $_SESSION['public_renter_last_submit'] ?? 0;

        if ($lastSubmit > 0 && (time() - $lastSubmit) < self::RATE_LIMIT_SECONDS) {
            log_error('Public renter form blocked by rate limit.');

            return false;
        }

        return true;
    }

    private function validatePublicData(array $input): array
    {
        $errors = [];
        $values = $this->extractValues($input);

        if ($values['name'] === '') {
            $errors['name'] = 'Họ tên không được để trống.';
        }

        if ($values['email'] === '') {
            $errors['email'] = 'Email không được để trống.';
        } elseif (!filter_var($values['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Email không đúng định dạng.';
        }

        if ($values['phone'] === '') {
            $errors['phone'] = 'Số điện thoại không được để trống.';
        }

        return ['errors' => $errors, 'values' => $values];
    }

    private function extractValues(array $input): array
    {
        return [
            'name' => trim($input['name'] ?? ''),
            'email' => trim($input['email'] ?? ''),
            'phone' => trim($input['phone'] ?? ''),
            'status' => 'new',
            'note' => trim($input['note'] ?? 'Đăng ký thuê thiết bị qua form công khai'),
        ];
    }
}
