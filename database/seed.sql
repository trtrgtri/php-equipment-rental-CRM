USE equipment_rental_crm;

-- Password: 123456
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin User', 'admin@example.com', '$2y$10$cEf1sJ2HtnSG9If7xdbn.O3CIYa/xFnoFx4Y.rf1PESl2rLaRdu1C', 'admin'),
('Staff User', 'staff@example.com', '$2y$10$cEf1sJ2HtnSG9If7xdbn.O3CIYa/xFnoFx4Y.rf1PESl2rLaRdu1C', 'staff');

INSERT INTO renters (name, email, phone, status, note) VALUES
('Anna Nguyen', 'anna@example.com', '0909000001', 'new', 'Quan tâm thuê máy khoan'),
('Ben Tran', 'ben@example.com', '0909000002', 'contacted', 'Đã gọi tư vấn lần 1'),
('Chi Le', 'chi.le@example.com', '0909000003', 'approved', 'Khách thuê thường xuyên'),
('Dung Pham', 'dung.pham@example.com', '0909000004', 'new', 'Cần thuê máy phát điện'),
('Emily Vo', 'emily.vo@example.com', '0909000005', 'contacted', 'Hỏi giá thuê thang nhôm'),
('Frank Hoang', 'frank.h@example.com', '0909000006', 'approved', 'Thuê máy cắt cỏ dài hạn'),
('Giang Mai', 'giang.mai@example.com', '0909000007', 'inactive', 'Không còn nhu cầu'),
('Hoa Bui', 'hoa.bui@example.com', '0909000008', 'new', 'Đăng ký qua form công khai'),
('Ivan Do', 'ivan.do@example.com', '0909000009', 'contacted', 'Chờ xác nhận CMND'),
('Jenny Lam', 'jenny.lam@example.com', '0909000010', 'approved', 'Khách doanh nghiệp'),
('Khanh Ngo', 'khanh.ngo@example.com', '0909000011', 'new', 'Thuê máy nén khí 1 tuần'),
('Linh Truong', 'linh.truong@example.com', '0909000012', 'contacted', 'Hỏi chính sách đặt cọc'),
('Minh Phan', 'minh.phan@example.com', '0909000013', 'approved', 'Thuê thiết bị xây dựng'),
('Nga Tran', 'nga.tran@example.com', '0909000014', 'new', 'Cần máy đo laser'),
('Oscar Vu', 'oscar.vu@example.com', '0909000015', 'inactive', 'Đã chuyển sang đối tác khác'),
('Phuong Dang', 'phuong.dang@example.com', '0909000016', 'contacted', 'Đang so sánh giá'),
('Quan Ly', 'quan.ly@example.com', '0909000017', 'approved', 'Khách VIP'),
('Rose Nguyen', 'rose.nguyen@example.com', '0909000018', 'new', 'Thuê máy hút bụi công nghiệp'),
('Son Ha', 'son.ha@example.com', '0909000019', 'contacted', 'Cần giao thiết bị tận nơi'),
('Tam Vo', 'tam.vo@example.com', '0909000020', 'approved', 'Thuê máy đầm bê tông'),
('Uyen Cao', 'uyen.cao@example.com', '0909000021', 'new', 'Hỏi thuê máy hàn'),
('Vinh Le', 'vinh.le@example.com', '0909000022', 'contacted', 'Chờ duyệt hồ sơ');

INSERT INTO rentals (rental_code, renter_name, renter_email, equipment_name, total_amount, status) VALUES
('RNT-2026-0001', 'Anna Nguyen', 'anna@example.com', 'Máy khoan Bosch GSB 550', 350000, 'pending'),
('RNT-2026-0002', 'Ben Tran', 'ben@example.com', 'Máy cắt cỏ Honda GC135', 850000, 'active'),
('RNT-2026-0003', 'Chi Le', 'chi.le@example.com', 'Máy phát điện 5kW Denyo', 2500000, 'returned'),
('RNT-2026-0004', 'Dung Pham', 'dung.pham@example.com', 'Thang nhôm 6m Nikawa', 450000, 'pending'),
('RNT-2026-0005', 'Emily Vo', 'emily.vo@example.com', 'Máy nén khí Puma 1HP', 1200000, 'active'),
('RNT-2026-0006', 'Frank Hoang', 'frank.h@example.com', 'Máy cắt cỏ Honda GC135', 900000, 'overdue'),
('RNT-2026-0007', 'Giang Mai', 'giang.mai@example.com', 'Máy đầm bê tông Mikasa', 650000, 'cancelled'),
('RNT-2026-0008', 'Hoa Bui', 'hoa.bui@example.com', 'Máy khoan Makita HP1630', 380000, 'pending'),
('RNT-2026-0009', 'Ivan Do', 'ivan.do@example.com', 'Máy hàn điện tử Jasic 200A', 550000, 'active'),
('RNT-2026-0010', 'Jenny Lam', 'jenny.lam@example.com', 'Máy phát điện 3kW Elemax', 1800000, 'returned'),
('RNT-2026-0011', 'Khanh Ngo', 'khanh.ngo@example.com', 'Máy nén khí Puma 1HP', 1100000, 'pending'),
('RNT-2026-0012', 'Linh Truong', 'linh.truong@example.com', 'Máy đo laser Bosch GLL 3-80', 750000, 'active'),
('RNT-2026-0013', 'Minh Phan', 'minh.phan@example.com', 'Máy cưa bàn Dewalt DWE7485', 950000, 'returned'),
('RNT-2026-0014', 'Nga Tran', 'nga.tran@example.com', 'Máy hút bụi công nghiệp Karcher', 420000, 'pending'),
('RNT-2026-0015', 'Oscar Vu', 'oscar.vu@example.com', 'Thang nhôm 8m Nikawa', 580000, 'cancelled'),
('RNT-2026-0016', 'Phuong Dang', 'phuong.dang@example.com', 'Máy khoan Bosch GSB 550', 360000, 'active'),
('RNT-2026-0017', 'Quan Ly', 'quan.ly@example.com', 'Máy phát điện 10kW Denyo', 4500000, 'active'),
('RNT-2026-0018', 'Rose Nguyen', 'rose.nguyen@example.com', 'Máy hút bụi công nghiệp Karcher', 400000, 'returned'),
('RNT-2026-0019', 'Son Ha', 'son.ha@example.com', 'Máy đầm bê tông Mikasa', 680000, 'overdue'),
('RNT-2026-0020', 'Tam Vo', 'tam.vo@example.com', 'Máy đầm bê tông Mikasa', 700000, 'pending'),
('RNT-2026-0021', 'Uyen Cao', 'uyen.cao@example.com', 'Máy hàn điện tử Jasic 200A', 520000, 'active'),
('RNT-2026-0022', 'Vinh Le', 'vinh.le@example.com', 'Máy cưa bàn Dewalt DWE7485', 980000, 'pending');
