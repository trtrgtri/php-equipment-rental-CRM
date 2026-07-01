USE equipment_rental_crm;

-- Password: 123456
INSERT INTO users (name, email, password_hash, role) VALUES
('Admin User', 'admin@example.com', '$2y$10$cEf1sJ2HtnSG9If7xdbn.O3CIYa/xFnoFx4Y.rf1PESl2rLaRdu1C', 'admin'),
('Staff User', 'staff@example.com', '$2y$10$cEf1sJ2HtnSG9If7xdbn.O3CIYa/xFnoFx4Y.rf1PESl2rLaRdu1C', 'staff');

INSERT INTO renters (name, email, phone, status, note, created_at) VALUES
('Nguyễn Thị An', 'nguyen.an@example.com', '0909000001', 'new', 'Quan tâm thuê máy khoan', '2026-01-03 09:00:00'),
('Trần Văn Bình', 'tran.binh@example.com', '0909000002', 'contacted', 'Đã gọi tư vấn lần 1', '2026-01-08 14:30:00'),
('Lê Thị Chi', 'le.chi@example.com', '0909000003', 'approved', 'Khách thuê thường xuyên', '2026-01-15 11:20:00'),
('James Wilson', 'james.wilson@example.com', '0909000004', 'new', 'Khách nước ngoài cần máy phát điện', '2026-01-22 16:45:00'),
('Phạm Minh Đức', 'pham.duc@example.com', '0909000005', 'contacted', 'Hỏi giá thuê thang nhôm', '2026-02-01 08:00:00'),
('Emily Roberts', 'emily.roberts@example.com', '0909000006', 'approved', 'Thuê máy cắt cỏ dài hạn', '2026-02-10 10:15:00'),
('Hoàng Thị Giang', 'hoang.giang@example.com', '0909000007', 'inactive', 'Không còn nhu cầu', '2026-02-18 13:40:00'),
('Bùi Văn Hòa', 'bui.hoa@example.com', '0909000008', 'new', 'Đăng ký qua form công khai', '2026-02-25 09:55:00'),
('David Chen', 'david.chen@example.com', '0909000009', 'contacted', 'Chờ xác nhận CMND', '2026-03-05 15:10:00'),
('Lâm Thị Mai', 'lam.mai@example.com', '0909000010', 'approved', 'Khách doanh nghiệp', '2026-03-12 11:30:00'),
('Ngô Văn Khánh', 'ngo.khanh@example.com', '0909000011', 'new', 'Thuê máy nén khí 1 tuần', '2026-03-20 08:25:00'),
('Trương Thị Linh', 'truong.linh@example.com', '0909000012', 'contacted', 'Hỏi chính sách đặt cọc', '2026-03-28 14:00:00'),
('Phan Minh Tuấn', 'phan.tuan@example.com', '0909000013', 'approved', 'Thuê thiết bị xây dựng', '2026-04-02 10:50:00'),
('Trần Thị Nga', 'tran.nga@example.com', '0909000014', 'new', 'Cần máy đo laser', '2026-04-10 16:20:00'),
('Oscar Schmidt', 'oscar.schmidt@example.com', '0909000015', 'inactive', 'Đã chuyển sang đối tác khác', '2026-04-18 09:35:00'),
('Đặng Thị Phương', 'dang.phuong@example.com', '0909000016', 'contacted', 'Đang so sánh giá', '2026-04-25 13:15:00'),
('Lý Quang Huy', 'ly.huy@example.com', '0909000017', 'approved', 'Khách VIP', '2026-05-03 08:40:00'),
('Rose Williams', 'rose.williams@example.com', '0909000018', 'new', 'Thuê máy hút bụi công nghiệp', '2026-05-11 12:05:00'),
('Hà Văn Sơn', 'ha.son@example.com', '0909000019', 'contacted', 'Cần giao thiết bị tận nơi', '2026-05-19 15:30:00'),
('Võ Thị Tám', 'vo.tam@example.com', '0909000020', 'approved', 'Thuê máy đầm bê tông', '2026-05-27 10:20:00'),
('Cao Thị Uyên', 'cao.uyen@example.com', '0909000021', 'new', 'Hỏi thuê máy hàn', '2026-06-04 14:45:00'),
('Lê Văn Vinh', 'le.vinh@example.com', '0909000022', 'contacted', 'Chờ duyệt hồ sơ', '2026-06-12 09:10:00');

INSERT INTO rentals (rental_code, renter_name, renter_email, equipment_name, total_amount, status, created_at) VALUES
('RNT-2026-0001', 'Nguyễn Thị An', 'nguyen.an@example.com', 'Máy khoan Bosch GSB 550', 350000, 'pending', '2026-02-14 08:30:00'),
('RNT-2026-0002', 'Trần Văn Bình', 'tran.binh@example.com', 'Máy cắt cỏ Honda GC135', 850000, 'active', '2026-02-20 11:00:00'),
('RNT-2026-0003', 'Lê Thị Chi', 'le.chi@example.com', 'Máy phát điện 5kW Denyo', 2500000, 'returned', '2026-02-28 15:20:00'),
('RNT-2026-0004', 'James Wilson', 'james.wilson@example.com', 'Thang nhôm 6m Nikawa', 450000, 'pending', '2026-03-08 09:45:00'),
('RNT-2026-0005', 'Phạm Minh Đức', 'pham.duc@example.com', 'Máy nén khí Puma 1HP', 1200000, 'active', '2026-03-15 13:10:00'),
('RNT-2026-0006', 'Emily Roberts', 'emily.roberts@example.com', 'Máy cắt cỏ Honda GC135', 900000, 'overdue', '2026-03-22 10:35:00'),
('RNT-2026-0007', 'Hoàng Thị Giang', 'hoang.giang@example.com', 'Máy đầm bê tông Mikasa', 650000, 'cancelled', '2026-03-30 16:50:00'),
('RNT-2026-0008', 'Bùi Văn Hòa', 'bui.hoa@example.com', 'Máy khoan Makita HP1630', 380000, 'pending', '2026-04-05 08:15:00'),
('RNT-2026-0009', 'David Chen', 'david.chen@example.com', 'Máy hàn điện tử Jasic 200A', 550000, 'active', '2026-04-12 12:40:00'),
('RNT-2026-0010', 'Lâm Thị Mai', 'lam.mai@example.com', 'Máy phát điện 3kW Elemax', 1800000, 'returned', '2026-04-20 14:25:00'),
('RNT-2026-0011', 'Ngô Văn Khánh', 'ngo.khanh@example.com', 'Máy nén khí Puma 1HP', 1100000, 'pending', '2026-04-28 09:00:00'),
('RNT-2026-0012', 'Trương Thị Linh', 'truong.linh@example.com', 'Máy đo laser Bosch GLL 3-80', 750000, 'active', '2026-05-06 11:55:00'),
('RNT-2026-0013', 'Phan Minh Tuấn', 'phan.tuan@example.com', 'Máy cưa bàn Dewalt DWE7485', 950000, 'returned', '2026-05-14 15:30:00'),
('RNT-2026-0014', 'Trần Thị Nga', 'tran.nga@example.com', 'Máy hút bụi công nghiệp Karcher', 420000, 'pending', '2026-05-22 08:50:00'),
('RNT-2026-0015', 'Oscar Schmidt', 'oscar.schmidt@example.com', 'Thang nhôm 8m Nikawa', 580000, 'cancelled', '2026-05-30 13:20:00'),
('RNT-2026-0016', 'Đặng Thị Phương', 'dang.phuong@example.com', 'Máy khoan Bosch GSB 550', 360000, 'active', '2026-06-06 10:05:00'),
('RNT-2026-0017', 'Lý Quang Huy', 'ly.huy@example.com', 'Máy phát điện 10kW Denyo', 4500000, 'active', '2026-06-14 16:40:00'),
('RNT-2026-0018', 'Rose Williams', 'rose.williams@example.com', 'Máy hút bụi công nghiệp Karcher', 400000, 'returned', '2026-06-20 09:25:00'),
('RNT-2026-0019', 'Hà Văn Sơn', 'ha.son@example.com', 'Máy đầm bê tông Mikasa', 680000, 'overdue', '2026-06-22 12:15:00'),
('RNT-2026-0020', 'Võ Thị Tám', 'vo.tam@example.com', 'Máy đầm bê tông Mikasa', 700000, 'pending', '2026-06-24 14:50:00'),
('RNT-2026-0021', 'Cao Thị Uyên', 'cao.uyen@example.com', 'Máy hàn điện tử Jasic 200A', 520000, 'active', '2026-06-26 08:35:00'),
('RNT-2026-0022', 'Lê Văn Vinh', 'le.vinh@example.com', 'Máy cưa bàn Dewalt DWE7485', 980000, 'pending', '2026-06-28 11:45:00');
