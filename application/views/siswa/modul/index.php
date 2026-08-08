<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kursus - WorkyWay</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }

        body {
            background: #ffffff;
            min-height: 100vh;
        }

        .dashboard-container {
            max-width: 1400px;
            margin: 0 auto;
            background: white;
        }

        /* Navbar Styles */
        .navbar {
            background: white;
            padding: 20px 40px;
            border-bottom: 1px solid #eaeaea;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }

        .navbar-brand h2 {
            color: #4f46e5;
            font-size: 28px;
            font-weight: 800;
            letter-spacing: 0.5px;
        }

        .navbar-menu ul {
            display: flex;
            list-style: none;
            gap: 30px;
            margin: 0;
            padding: 0;
        }

        .navbar-menu a {
            text-decoration: none;
            color: #666;
            font-weight: 500;
            font-size: 16px;
            padding: 8px 16px;
            border-radius: 8px;
            transition: all 0.3s ease;
        }

        .navbar-menu a:hover {
            color: #4f46e5;
            background: #f5f3ff;
        }

        .navbar-menu .active a {
            color: #4f46e5;
            background: #f5f3ff;
            font-weight: 600;
        }

        /* Main Content */
        .modul-content {
            display: flex;
            min-height: calc(100vh - 120px);
        }

        /* Sidebar Modul */
        .modul-sidebar {
            width: 320px;
            background: #f8fafc;
            border-right: 1px solid #e2e8f0;
            padding: 30px;
            overflow-y: auto;
        }

        .sidebar-header {
            margin-bottom: 30px;
        }

        .sidebar-header h1 {
            color: #1e293b;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .sidebar-header p {
            color: #64748b;
            font-size: 14px;
        }

        /* Sidebar Menu */
        .sidebar-menu {
            margin-top: 30px;
        }

        .menu-section {
            margin-bottom: 30px;
        }

        .menu-title {
            font-size: 16px;
            font-weight: 600;
            color: #475569;
            margin-bottom: 15px;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }

        .menu-items {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .menu-item {
            display: flex;
            align-items: center;
            padding: 12px 16px;
            border-radius: 12px;
            text-decoration: none;
            color: #334155;
            transition: all 0.3s ease;
            border: 1px solid transparent;
            gap: 12px;
            cursor: pointer;
            background: white;
        }

        .menu-item:hover {
            border-color: #e2e8f0;
            transform: translateX(4px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .menu-item.active {
            background: #f5f3ff;
            border-color: #c7d2fe;
        }

        .menu-item-checkbox {
            position: relative;
            width: 20px;
            height: 20px;
            flex-shrink: 0;
        }

        .menu-item-checkbox input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
            width: 100%;
            height: 100%;
            z-index: 2;
        }

        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            width: 20px;
            height: 20px;
            border: 2px solid #cbd5e1;
            border-radius: 4px;
            background-color: white;
            transition: all 0.3s ease;
        }

        .menu-item-checkbox input:checked ~ .checkmark {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .menu-item-checkbox input:checked ~ .checkmark:after {
            content: "✓";
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 12px;
            font-weight: bold;
        }

        .menu-item-icon {
            width: 40px;
            height: 40px;
            background: #f1f5f9;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            flex-shrink: 0;
        }

        .menu-item-text {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .menu-item-text strong {
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 2px;
        }

        .menu-item-text span {
            font-size: 12px;
            color: #64748b;
        }

        /* Konten Utama */
        .modul-main {
            flex: 1;
            padding: 40px;
            overflow-y: auto;
        }

        /* Tab Styles */
        .tabs {
            display: flex;
            gap: 8px;
            margin-bottom: 30px;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 2px;
        }

        .tab {
            padding: 12px 24px;
            background: transparent;
            border: none;
            border-bottom: 3px solid transparent;
            font-size: 16px;
            font-weight: 500;
            color: #64748b;
            cursor: pointer;
            transition: all 0.3s ease;
            border-radius: 8px 8px 0 0;
        }

        .tab:hover {
            color: #4f46e5;
            background: #f5f3ff;
        }

        .tab.active {
            color: #4f46e5;
            border-bottom-color: #4f46e5;
            background: #f5f3ff;
        }

        .tab-content {
            display: none;
        }

        .tab-content.active {
            display: block;
        }

        /* Header */
        .main-header {
            margin-bottom: 40px;
        }

        .main-header h1 {
            color: #1e293b;
            font-size: 32px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .main-header p {
            color: #64748b;
            font-size: 16px;
        }

        /* Progress Status */
        .progress-status {
            background: #f0f9ff;
            border: 1px solid #e0f2fe;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            display: flex;
            align-items: center;
            gap: 16px;
        }

        .progress-icon {
            width: 40px;
            height: 40px;
            background: #0ea5e9;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 18px;
        }

        .progress-info h4 {
            color: #1e293b;
            margin-bottom: 4px;
            font-size: 16px;
        }

        .progress-info p {
            color: #64748b;
            font-size: 14px;
        }

        /* Materi Container */
        .materi-container {
            display: flex;
            flex-direction: column;
            gap: 32px;
        }

        .materi-section {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 32px;
        }

        .section-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }

        .section-icon {
            width: 40px;
            height: 40px;
            background: #f5f3ff;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4f46e5;
            font-size: 20px;
        }

        .section-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
        }

        .teks-materi {
            line-height: 1.8;
            color: #475569;
        }

        .teks-materi p {
            margin-bottom: 16px;
        }

        .code-container {
            background: #1e293b;
            border-radius: 12px;
            padding: 24px;
            overflow-x: auto;
            margin: 16px 0;
        }

        pre {
            color: #e2e8f0;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            line-height: 1.6;
            margin: 0;
        }

        .materi-konten {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            margin-top: 20px;
        }

        .materi-konten h3 {
            color: #1e293b;
            font-size: 18px;
            margin-bottom: 16px;
            padding-bottom: 8px;
            border-bottom: 2px solid #e2e8f0;
        }

        .materi-konten-content {
            color: #475569;
            line-height: 1.8;
        }

        .materi-konten-content p {
            margin-bottom: 16px;
        }

        /* Navigation Buttons */
        .nav-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
            padding-top: 40px;
            border-top: 1px solid #e2e8f0;
        }

        .nav-btn {
            padding: 14px 28px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
            font-size: 14px;
        }

        .nav-btn.prev {
            background: #f8fafc;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }

        .nav-btn.prev:hover {
            background: #f1f5f9;
        }

        .nav-btn.next {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
        }

        .nav-btn.next:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        /* Diskusi Section */
        .discussion-section {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
        }

        .discussion-section h4 {
            color: #1e293b;
            font-size: 18px;
            margin-bottom: 16px;
        }

        .message-input textarea {
            width: 100%;
            padding: 12px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            resize: vertical;
            font-size: 14px;
            min-height: 80px;
        }

        .message-input button {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
            border: none;
            padding: 10px 24px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 12px;
            transition: all 0.3s ease;
        }

        .message-input button:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
        }

        .recent-discussions h4 {
            color: #1e293b;
            font-size: 18px;
            margin-bottom: 20px;
        }

        .discussion-item {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 16px;
            transition: all 0.3s ease;
            border-left: 4px solid #007bff;
        }

        .discussion-item:hover {
            box-shadow: 0 4px 12px rgba(0,0,0,0.08);
        }

        .discussion-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 12px;
        }

        .discussion-user {
            display: flex;
            flex-direction: column;
        }

        .discussion-user strong {
            color: #1e293b;
            font-size: 15px;
        }

        .discussion-user small {
            color: #6c757d;
            font-size: 12px;
        }

        .discussion-badge {
            background: #e9ecef;
            color: #495057;
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: 500;
        }

        .discussion-message {
            color: #475569;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        .reply-btn {
            background: transparent;
            border: 1px solid #007bff;
            color: #007bff;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .reply-btn:hover {
            background: #007bff;
            color: white;
        }

        .reply-form {
            margin-top: 16px;
            padding-top: 16px;
            border-top: 1px solid #e2e8f0;
            display: none;
        }

        .reply-form textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ced4da;
            border-radius: 5px;
            resize: vertical;
            font-size: 14px;
            min-height: 60px;
            margin-bottom: 10px;
        }

        .reply-form button {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        .replies {
            margin-top: 16px;
            padding-left: 20px;
            border-left: 3px solid #28a745;
        }

        .reply-item {
            background: #f8f9fa;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 12px;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .reply-item strong {
            color: #1e293b;
            font-size: 13px;
        }

        .reply-item small {
            color: #6c757d;
            font-size: 11px;
            margin-left: 8px;
        }

        .reply-item p {
            color: #475569;
            margin-top: 6px;
            line-height: 1.5;
        }

        /* Notification */
        .flash-notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 24px;
            border-radius: 12px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            z-index: 9999;
            animation: slideIn 0.3s ease;
            max-width: 400px;
        }

        .flash-notification.success {
            background: #10b981;
            color: white;
        }

        .flash-notification.error {
            background: #ef4444;
            color: white;
        }

        @keyframes slideIn {
            from {
                transform: translateX(100%);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(100%);
                opacity: 0;
            }
        }

        /* =============== STYLE UNTUK TUGAS =============== */
        
        .tugas-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
        }

        .tugas-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .tugas-card:hover {
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .tugas-card.status-dinilai {
            border-left: 4px solid #10b981;
        }

        .tugas-card.status-dikumpulkan {
            border-left: 4px solid #3b82f6;
        }

        .tugas-card.status-belum {
            border-left: 4px solid #fbbf24;
        }

        .tugas-card.status-terlambat {
            border-left: 4px solid #ef4444;
        }

        .tugas-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .tugas-title {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .tugas-title h3 {
            color: #1e293b;
            font-size: 20px;
            font-weight: 600;
            margin: 0;
        }

        .tugas-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-dinilai .tugas-badge {
            background: #d1fae5;
            color: #065f46;
        }

        .status-dikumpulkan .tugas-badge {
            background: #dbeafe;
            color: #1e40af;
        }

        .status-belum .tugas-badge {
            background: #fef3c7;
            color: #92400e;
        }

        .status-terlambat .tugas-badge {
            background: #fee2e2;
            color: #991b1b;
        }

        .tugas-meta {
            display: flex;
            gap: 20px;
            color: #64748b;
            font-size: 14px;
        }

        .tugas-deadline {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .deadline-terlambat {
            color: #ef4444;
            font-weight: 600;
        }

        .tugas-body {
            padding: 24px;
        }

        .tugas-description {
            color: #475569;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .tugas-info {
            display: flex;
            gap: 20px;
            padding: 16px;
            background: #f1f5f9;
            border-radius: 8px;
            font-size: 14px;
            color: #475569;
        }

        .download-template {
            color: #4f46e5;
            text-decoration: none;
            font-weight: 500;
        }

        .download-template:hover {
            text-decoration: underline;
        }

        .pengumpulan-info {
            margin-top: 20px;
            padding: 20px;
            background: #f0f9ff;
            border-radius: 12px;
            border: 1px solid #e0f2fe;
        }

        .pengumpulan-info h4 {
            color: #1e293b;
            margin-bottom: 12px;
            font-size: 16px;
        }

        .pengumpulan-details {
            color: #475569;
            line-height: 1.6;
        }

        .pengumpulan-details p {
            margin-bottom: 8px;
        }

        .pengumpulan-details a {
            color: #4f46e5;
            text-decoration: none;
        }

        .pengumpulan-details a:hover {
            text-decoration: underline;
        }

        .nilai {
            color: #10b981;
            font-weight: 600;
        }

        .tugas-footer {
            padding: 20px 24px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 12px;
            align-items: center;
        }

        .btn-kumpul, .btn-detail, .btn-hapus {
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.3s ease;
            border: none;
            font-size: 14px;
        }

        .btn-kumpul {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
        }

        .btn-kumpul:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        .btn-detail {
            background: #f1f5f9;
            color: #475569;
            border: 1px solid #cbd5e1;
        }

        .btn-detail:hover {
            background: #e2e8f0;
        }

        .btn-hapus {
            background: #fee2e2;
            color: #dc2626;
            border: 1px solid #fecaca;
        }

        .btn-hapus:hover {
            background: #fecaca;
        }

        .warning-text {
            color: #ef4444;
            font-size: 14px;
            margin-left: auto;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0,0,0,0.5);
        }

        .modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 16px;
            width: 90%;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
        }

        .modal-header {
            padding: 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            color: #1e293b;
            margin: 0;
            font-size: 24px;
        }

        .close-modal {
            color: #64748b;
            font-size: 28px;
            font-weight: bold;
            cursor: pointer;
        }

        .close-modal:hover {
            color: #1e293b;
        }

        .modal-body {
            padding: 24px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #334155;
            font-weight: 500;
        }

        .form-group input[type="file"],
        .form-group input[type="url"],
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 16px;
        }

        .form-group input:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #4f46e5;
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
        }

        .form-group small {
            color: #64748b;
            font-size: 12px;
            margin-top: 4px;
            display: block;
        }

        .modal-footer {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 30px;
        }

        .btn-cancel, .btn-submit {
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            border: none;
            font-size: 16px;
        }

        .btn-cancel {
            background: #f1f5f9;
            color: #475569;
        }

        .btn-cancel:hover {
            background: #e2e8f0;
        }

        .btn-submit {
            background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%);
            color: white;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.4);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 40px 20px;
            color: #64748b;
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.5;
        }

        .empty-state-title {
            color: #64748b;
            margin-bottom: 8px;
        }

        .empty-state-text {
            font-size: 14px;
        }

        /* Materi Info Box */
        .materi-info-box {
            background: #f0f9ff;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 30px;
            border: 1px solid #e0f2fe;
        }

        .materi-info-header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 15px;
        }

        .materi-info-icon {
            width: 50px;
            height: 50px;
            background: #3b82f6;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 24px;
        }

        .materi-info-text h3 {
            color: #1e293b;
            margin: 0;
            font-size: 20px;
        }

        .materi-info-text p {
            color: #64748b;
            margin: 4px 0 0;
            font-size: 14px;
        }

        .materi-info-description {
            background: white;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e2e8f0;
            color: #475569;
            line-height: 1.6;
        }

        /* Ringkasan Styles */
        .ringkasan-list {
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .ringkasan-item {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }

        .ringkasan-bullet {
            width: 24px;
            height: 24px;
            background: #f5f3ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #4f46e5;
            font-size: 12px;
            font-weight: 600;
            flex-shrink: 0;
        }

        .ringkasan-text {
            color: #475569;
            line-height: 1.6;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .modul-content {
                flex-direction: column;
            }
            
            .modul-sidebar {
                width: 100%;
                border-right: none;
                border-bottom: 1px solid #e2e8f0;
            }
        }

        @media (max-width: 768px) {
            .navbar {
                flex-direction: column;
                gap: 20px;
            }
            
            .navbar-menu ul {
                flex-wrap: wrap;
                justify-content: center;
                gap: 20px;
            }
            
            .modul-sidebar, .modul-main {
                padding: 20px;
            }
            
            .nav-buttons {
                flex-direction: column;
                gap: 16px;
            }
            
            .nav-btn {
                width: 100%;
                justify-content: center;
            }
            
            .tabs {
                overflow-x: auto;
                flex-wrap: nowrap;
            }
            
            .tab {
                white-space: nowrap;
            }
            
            .tugas-footer {
                flex-wrap: wrap;
            }
            
            .btn-kumpul, .btn-detail, .btn-hapus {
                flex: 1;
                min-width: 120px;
            }
        }
    </style>
</head>
<body>
    <!-- Notification for flash data -->
    <?php if($this->session->flashdata('success')): ?>
    <div class="flash-notification success" id="flash-notification">
        <?php echo $this->session->flashdata('success'); ?>
    </div>
    <?php elseif($this->session->flashdata('error')): ?>
    <div class="flash-notification error" id="flash-notification">
        <?php echo $this->session->flashdata('error'); ?>
    </div>
    <?php endif; ?>

    <div class="dashboard-container">
        <!-- Navbar -->
        <nav class="navbar">
            <div class="navbar-left">
                <div class="navbar-brand">
                    <h2>WorkyWay</h2>
                </div>
            </div>
            <div class="navbar-menu">
                <ul>
                    <li><a href="<?php echo site_url('siswa/dashboard'); ?>">Dashboard</a></li>
                    <li><a href="<?php echo site_url('siswa/kursus'); ?>">Kursus</a></li>
                  
                    <li><a href="<?php echo site_url('siswa/profil'); ?>">Profil</a></li>
                    <li><a href="<?php echo site_url('login/logout'); ?>">Logout</a></li>
                </ul>
            </div>
        </nav>

        <!-- Main Content -->
        <div class="modul-content">
            <!-- Sidebar Modul -->
            <div class="modul-sidebar">
                <div class="sidebar-header">
                    <h1><?php echo isset($kursus) ? $kursus->judul_kursus : 'Kelas Online'; ?></h1>
                    <p><?php echo isset($kursus) ? $kursus->deskripsi : ''; ?></p>
                </div>

                <!-- Sidebar Menu -->
                <div class="sidebar-menu">
                    <div class="menu-section">
                        <h3 class="menu-title">Modul Pembelajaran</h3>
                        <div class="menu-items">
                            <?php if(isset($materi_list) && !empty($materi_list)): ?>
                                <?php foreach($materi_list as $index => $materi): ?>
                                    <?php 
                                        $is_active = (isset($materi_aktif) && $materi_aktif->materi_id == $materi->materi_id);
                                        $is_completed = (isset($materi_progress) && $materi_progress === 'completed');
                                        $materi_url = site_url('siswa/modul/materi/' . $materi->materi_id);
                                    ?>
                                    <div class="menu-item <?php echo $is_active ? 'active' : ''; ?>" 
                                         onclick="window.location.href='<?php echo $materi_url; ?>'">
                                        <div class="menu-item-checkbox">
                                            <input type="checkbox" <?php echo $is_completed ? 'checked' : ''; ?> 
                                                   onchange="updateProgress(<?php echo $materi->materi_id; ?>, this)">
                                            <span class="checkmark"></span>
                                        </div>
                                        <div class="menu-item-text">
                                            <strong>Modul <?php echo $index + 1; ?></strong>
                                            <span><?php echo $materi->judul_materi; ?></span>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="menu-item">
                                    <div class="menu-item-text">
                                        <strong>Belum ada modul</strong>
                                        <span>Materi akan segera tersedia</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Konten Utama -->
            <div class="modul-main">
                <!-- Tabs -->
                <div class="tabs">
                    <?php 
                    $active_tab_materi = (!isset($active_tab) || $active_tab == 'materi') ? 'active' : '';
                    $active_tab_tugas = (isset($active_tab) && $active_tab == 'tugas') ? 'active' : '';
                    $active_tab_diskusi = (isset($active_tab) && $active_tab == 'diskusi') ? 'active' : '';
                    ?>
                    <button class="tab <?php echo $active_tab_materi; ?>" onclick="switchTab('materi')">Materi</button>
                    <button class="tab <?php echo $active_tab_tugas; ?>" onclick="switchTab('tugas')">Tugas</button>
                    <button class="tab <?php echo $active_tab_diskusi; ?>" onclick="switchTab('diskusi')">Diskusi</button>
                </div>

                <!-- Tab Content - Materi -->
                <div id="tab-materi" class="tab-content <?php echo $active_tab_materi; ?>">
                    <?php if(isset($materi_aktif)): ?>
                        <?php 
                            $tipe_icon = '📄';
                            switch($materi_aktif->tipe_materi) {
                                case 'video': $tipe_icon = '🎬'; break;
                                case 'pdf': $tipe_icon = '📕'; break;
                                case 'kuis': $tipe_icon = '📝'; break;
                                case 'link': $tipe_icon = '🔗'; break;
                            }
                            $tanggal = date('d M Y', strtotime($materi_aktif->tanggal_upload));
                            $is_completed = (isset($materi_progress) && $materi_progress === 'completed');
                        ?>
                        
                        <div class="main-header">
                            <h1><?php echo $tipe_icon . ' ' . $materi_aktif->judul_materi; ?></h1>
                            <p><?php echo date('d M Y', strtotime($materi_aktif->tanggal_upload)); ?></p>
                        </div>

                        <!-- Progress Status -->
                        <div class="progress-status">
                            <div class="progress-icon">
                                <?php echo $is_completed ? '✓' : '📖'; ?>
                            </div>
                            <div class="progress-info">
                                <h4><?php echo $is_completed ? 'Modul Telah Selesai' : 'Modul Sedang Dipelajari'; ?></h4>
                                <p><?php echo $is_completed ? 'Anda telah menyelesaikan modul ini' : 'Pelajari materi dan selesaikan tugas untuk menyelesaikan modul'; ?></p>
                            </div>
                        </div>

                        <!-- Materi Container -->
                        <div class="materi-container">
                            <!-- Deskripsi Materi -->
                            <div class="materi-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        📚
                                    </div>
                                    <h2 class="section-title">Deskripsi Materi</h2>
                                </div>
                                
                                <div class="teks-materi">
                                    <?php if(!empty($materi_aktif->deskripsi)): ?>
                                        <p><?php echo nl2br(htmlspecialchars($materi_aktif->deskripsi)); ?></p>
                                    <?php else: ?>
                                        <p>Materi ini merupakan bagian dari kursus <strong><?php echo isset($kursus) ? $kursus->judul_kursus : ''; ?></strong>.</p>
                                        <p>Silakan pelajari materi dengan seksama dan selesaikan semua tugas yang diberikan untuk mendapatkan pemahaman yang komprehensif.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Konten Materi -->
                            <div class="materi-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        📄
                                    </div>
                                    <h2 class="section-title">Konten Materi</h2>
                                </div>
                                
                                <div class="teks-materi">
                                    <?php if(!empty($materi_aktif->konten)): ?>
                                        <?php 
                                            switch($materi_aktif->tipe_materi) {
                                                case 'link':
                                                    echo '<p><strong>Link Eksternal:</strong></p>';
                                                    echo '<div class="code-container">';
                                                    echo '<pre><code>' . htmlspecialchars($materi_aktif->konten) . '</code></pre>';
                                                    echo '</div>';
                                                    echo '<p style="margin-top: 16px;"><a href="' . htmlspecialchars($materi_aktif->konten) . '" target="_blank" style="color: #4f46e5; text-decoration: none; font-weight: 500;">Klik untuk mengakses link →</a></p>';
                                                    break;
                                                case 'kuis':
                                                    echo '<p><strong>Kuis/Latihan Soal:</strong></p>';
                                                    echo '<p>' . nl2br(htmlspecialchars($materi_aktif->konten)) . '</p>';
                                                    break;
                                                default:
                                                    echo nl2br(htmlspecialchars($materi_aktif->konten));
                                            }
                                        ?>
                                    <?php else: ?>
                                        <p>Materi ini merupakan bagian dari kursus <strong><?php echo isset($kursus) ? $kursus->judul_kursus : ''; ?></strong>.</p>
                                        <p>Silakan pelajari materi dengan seksama dan selesaikan semua tugas yang diberikan untuk mendapatkan pemahaman yang komprehensif.</p>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <!-- Ringkasan -->
                            <div class="materi-section">
                                <div class="section-header">
                                    <div class="section-icon">
                                        📋
                                    </div>
                                    <h2 class="section-title">Ringkasan</h2>
                                </div>
                                
                                <div class="ringkasan-list">
                                    <div class="ringkasan-item">
                                        <div class="ringkasan-bullet">1</div>
                                        <div class="ringkasan-text">Materi ini merupakan bagian dari kursus <strong><?php echo isset($kursus) ? $kursus->judul_kursus : ''; ?></strong></div>
                                    </div>
                                    <div class="ringkasan-item">
                                        <div class="ringkasan-bullet">2</div>
                                        <div class="ringkasan-text">Tipe materi: <strong><?php echo $materi_aktif->tipe_materi; ?></strong></div>
                                    </div>
                                    <div class="ringkasan-item">
                                        <div class="ringkasan-bullet">3</div>
                                        <div class="ringkasan-text">Diunggah pada: <strong><?php echo $tanggal; ?></strong></div>
                                    </div>
                                    <div class="ringkasan-item">
                                        <div class="ringkasan-bullet">4</div>
                                        <div class="ringkasan-text">Status: <strong><?php echo $is_completed ? 'Selesai' : 'Belum selesai'; ?></strong></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Navigation Buttons -->
                        <div class="nav-buttons">
                            <?php 
                                // Cari materi sebelumnya dan selanjutnya
                                $prev_materi = null;
                                $next_materi = null;
                                if(isset($materi_list) && !empty($materi_list)) {
                                    foreach($materi_list as $index => $materi) {
                                        if($materi->materi_id == $materi_aktif->materi_id) {
                                            if($index > 0) {
                                                $prev_materi = $materi_list[$index-1];
                                            }
                                            if($index < count($materi_list) - 1) {
                                                $next_materi = $materi_list[$index+1];
                                            }
                                            break;
                                        }
                                    }
                                }
                            ?>
                            
                            <?php if($prev_materi): ?>
                            <button class="nav-btn prev" onclick="window.location.href='<?php echo site_url('siswa/modul/materi/' . $prev_materi->materi_id); ?>'">
                                ← Modul Sebelumnya
                            </button>
                            <?php else: ?>
                            <div></div>
                            <?php endif; ?>
                            
                            <?php if($next_materi): ?>
                            <button class="nav-btn next" onclick="window.location.href='<?php echo site_url('siswa/modul/materi/' . $next_materi->materi_id); ?>'">
                                Modul Selanjutnya →
                            </button>
                            <?php endif; ?>
                        </div>
                    <?php else: ?>
                        <div class="main-header">
                            <h1>Belum ada materi yang dipilih</h1>
                            <p>Silakan pilih materi dari sidebar di sebelah kiri</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab Content - Tugas -->
                <div id="tab-tugas" class="tab-content <?php echo $active_tab_tugas; ?>">
                    <?php if(isset($materi_aktif)): ?>
                    <div class="main-header">
                        <h1>📝 Tugas: <?php echo $materi_aktif->judul_materi; ?></h1>
                        <p>Kursus: <?php echo isset($kursus) ? $kursus->judul_kursus : ''; ?></p>
                    </div>

                    <!-- Informasi Materi -->
                    <div class="materi-info-box">
                        <div class="materi-info-header">
                            <div class="materi-info-icon">
                                <?php 
                                    switch($materi_aktif->tipe_materi) {
                                        case 'video': echo '🎬'; break;
                                        case 'pdf': echo '📕'; break;
                                        case 'kuis': echo '📝'; break;
                                        default: echo '📄';
                                    }
                                ?>
                            </div>
                            <div class="materi-info-text">
                                <h3><?php echo $materi_aktif->judul_materi; ?></h3>
                                <p>Tipe: <?php echo $materi_aktif->tipe_materi; ?> • Diunggah: <?php echo date('d M Y', strtotime($materi_aktif->tanggal_upload)); ?></p>
                            </div>
                        </div>
                        
                    </div>

                    <!-- Daftar Tugas -->
                    <div class="tugas-container" id="tugas-container">
                        <?php if(isset($tugas_list) && !empty($tugas_list)): ?>
                            <?php foreach($tugas_list as $tugas): ?>
                                <?php 
                                    $pengumpulan = isset($pengumpulan_tugas[$tugas->tugas_id]) ? $pengumpulan_tugas[$tugas->tugas_id] : null;
                                    $status = $tugas->status;
                                    $status_text = $tugas->status_text;
                                    $status_class = 'status-' . $status;
                                    $deadline_class = ($tugas->is_late && !$pengumpulan) ? 'deadline-terlambat' : '';
                                    $can_submit = (!$pengumpulan || $pengumpulan->status != 'dinilai');
                                ?>
                                <div class="tugas-card <?php echo $status_class; ?>">
                                    <div class="tugas-header">
                                        <div class="tugas-title">
                                            <h3><?php echo $tugas->judul_tugas; ?></h3>
                                            <span class="tugas-badge">
                                                <?php echo $status_text; ?>
                                            </span>
                                        </div>
                                        <div class="tugas-meta">
                                            <span class="tugas-deadline <?php echo $deadline_class; ?>">
                                                ⏰ <?php echo $tugas->deadline_formatted; ?>
                                            </span>
                                            <span class="tugas-score">🎯 Max: <?php echo $tugas->max_score; ?> poin</span>
                                        </div>
                                    </div>
                                    
                                    <div class="tugas-body">
                                        <div class="tugas-description">
                                            <p><?php echo nl2br(htmlspecialchars($tugas->deskripsi)); ?></p>
                                        </div>
                                        
                                        <div class="tugas-info">
                                            <span class="tugas-type">📋 <?php echo ucfirst($tugas->tipe_tugas); ?></span>
                                            <?php if($tugas->file_template): ?>
                                            <a href="<?php echo site_url('siswa/modul/download_template/' . $tugas->tugas_id); ?>" 
                                               class="download-template" target="_blank">
                                                📥 Download Template
                                            </a>
                                            <?php endif; ?>
                                        </div>
                                        
                                        <!-- Info Pengumpulan -->
                                        <?php if($pengumpulan): ?>
                                        <div class="pengumpulan-info">
                                            <h4>📤 Status Pengumpulan:</h4>
                                            <div class="pengumpulan-details">
                                                <p><strong>ID Pengumpulan:</strong> #<?php echo $pengumpulan->pengumpulan_id; ?></p>
                                                <p><strong>Tanggal Kumpul:</strong> <?php echo date('d M Y H:i', strtotime($pengumpulan->tanggal_kumpul)); ?></p>
                                                <?php if($pengumpulan->file_tugas): ?>
                                                <p><strong>File:</strong> 
                                                    <a href="<?php echo site_url('siswa/modul/download_tugas/' . $pengumpulan->pengumpulan_id); ?>" target="_blank">
                                                        <?php echo $pengumpulan->file_tugas; ?>
                                                    </a>
                                                </p>
                                                <?php endif; ?>
                                                <?php if($pengumpulan->link_pengumpulan): ?>
                                                <p><strong>Link:</strong> 
                                                    <a href="<?php echo $pengumpulan->link_pengumpulan; ?>" target="_blank">
                                                        <?php echo substr($pengumpulan->link_pengumpulan, 0, 50); ?>...
                                                    </a>
                                                </p>
                                                <?php endif; ?>
                                                <?php if($pengumpulan->catatan): ?>
                                                <p><strong>Catatan:</strong> <?php echo nl2br(htmlspecialchars($pengumpulan->catatan)); ?></p>
                                                <?php endif; ?>
                                                <?php if($pengumpulan->nilai): ?>
                                                <p><strong>Nilai:</strong> <span class="nilai"><?php echo $pengumpulan->nilai; ?>/<?php echo $tugas->max_score; ?></span></p>
                                                <?php endif; ?>
                                                <?php if($pengumpulan->feedback): ?>
                                                <p><strong>Feedback:</strong> <?php echo nl2br(htmlspecialchars($pengumpulan->feedback)); ?></p>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <div class="tugas-footer">
                                        <?php if($can_submit): ?>
                                        <button class="btn-kumpul" 
                                                onclick="showKumpulModal(<?php echo $tugas->tugas_id; ?>, <?php echo $materi_aktif->materi_id; ?>)">
                                            <?php echo $pengumpulan ? '✏️ Edit Pengumpulan' : '📤 Kumpulkan Tugas'; ?>
                                        </button>
                                        <?php endif; ?>
                                        
                                        
                                        
                                        <?php if($tugas->is_late && !$pengumpulan): ?>
                                        <span class="warning-text">⚠ Deadline telah berlalu</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="empty-state">
                                <div class="empty-state-icon">📝</div>
                                <h3 class="empty-state-title">Belum ada tugas</h3>
                                <p class="empty-state-text">Tidak ada tugas untuk materi ini</p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">📄</div>
                            <h3 class="empty-state-title">Pilih materi terlebih dahulu</h3>
                            <p class="empty-state-text">Silakan pilih materi dari sidebar untuk melihat tugas</p>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Tab Content - Diskusi -->
                <div id="tab-diskusi" class="tab-content <?php echo $active_tab_diskusi; ?>">
                    <div class="main-header">
                        <h1>💬 Diskusi: <?php echo isset($materi_aktif) ? $materi_aktif->judul_materi : 'Materi'; ?></h1>
                        <p>Forum diskusi untuk kursus <?php echo isset($kursus) ? $kursus->judul_kursus : ''; ?></p>
                    </div>

                    <!-- Form Diskusi -->
                    <?php if(isset($materi_aktif)): ?>
                    <div class="discussion-section">
                        <h4>Tulis Pertanyaan atau Komentar</h4>
                        <form action="<?php echo site_url('siswa/modul/tambah_diskusi'); ?>" method="POST">
                            <input type="hidden" name="kursus_id" value="<?php echo isset($kursus) ? $kursus->kursus_id : ''; ?>">
                            <input type="hidden" name="materi_id" value="<?php echo isset($materi_aktif) ? $materi_aktif->materi_id : ''; ?>">
                            <div class="message-input">
                                <textarea 
                                    name="pesan" 
                                    placeholder="Ketik pertanyaan atau komentar Anda di sini..." 
                                    rows="3"
                                    required></textarea>
                                <button type="submit">Kirim</button>
                            </div>
                        </form>
                    </div>

                    <!-- Daftar Diskusi -->
                    <div class="recent-discussions">
                        <h4>Diskusi untuk materi ini</h4>
                        
                        <div id="diskusi-container">
                            <?php if(isset($diskusi_list) && !empty($diskusi_list)): ?>
                                <?php foreach($diskusi_list as $diskusi): ?>
                                    <div class="discussion-item" id="diskusi-<?php echo $diskusi->forum_id; ?>">
                                        <div class="discussion-header">
                                            <div class="discussion-user">
                                                <strong><?php echo htmlspecialchars($diskusi->nama_lengkap); ?></strong>
                                                <small>
                                                    <?php 
                                                        $waktu = new DateTime($diskusi->tanggal_post);
                                                        $sekarang = new DateTime();
                                                        $selisih = $sekarang->diff($waktu);
                                                        
                                                        if ($selisih->d > 0) {
                                                            echo $selisih->d . ' hari lalu';
                                                        } elseif ($selisih->h > 0) {
                                                            echo $selisih->h . ' jam lalu';
                                                        } elseif ($selisih->i > 0) {
                                                            echo $selisih->i . ' menit lalu';
                                                        } else {
                                                            echo 'Baru saja';
                                                        }
                                                    ?>
                                                </small>
                                            </div>
                                            <span class="discussion-badge">
                                                <?php echo $diskusi->jumlah_balasan; ?> Balasan
                                            </span>
                                        </div>
                                        
                                        <div class="discussion-message">
                                            <?php echo nl2br(htmlspecialchars($diskusi->pesan)); ?>
                                        </div>
                                        
                                        <!-- Tombol Balas -->
                                        <button class="reply-btn" onclick="toggleReplyForm(<?php echo $diskusi->forum_id; ?>)">
                                            Balas
                                        </button>
                                        
                                        <!-- Form Balasan -->
                                        <div class="reply-form" id="reply-form-<?php echo $diskusi->forum_id; ?>">
                                           
<form method="POST" action="<?php echo site_url('siswa/modul/tambah_balasan'); ?>">
    <input type="hidden" name="thread_id" value="<?php echo $diskusi->forum_id; ?>">
    <input type="hidden" name="materi_id" value="<?php echo $materi_aktif->materi_id; ?>">
    
    <div class="form-group">
        <textarea name="balasan" class="form-control" rows="3" 
                  placeholder="Tulis balasan Anda..." required></textarea>
    </div>
    
    <button type="submit" class="btn btn-primary">
        <i class="fas fa-paper-plane"></i> Kirim Balasan
    </button>
</form>
                                        </div>
                                        
                                        <!-- Daftar Balasan -->
                                        <?php if(!empty($diskusi->balasan)): ?>
                                            <div class="replies" id="replies-<?php echo $diskusi->forum_id; ?>">
                                                <?php foreach($diskusi->balasan as $balasan): ?>
                                                    <div class="reply-item">
                                                        <strong><?php echo htmlspecialchars($balasan->nama_pengirim); ?></strong>
                                                        <small>
                                                            <?php 
                                                                $waktu_balasan = new DateTime($balasan->tanggal_post);
                                                                $selisih_balasan = $sekarang->diff($waktu_balasan);
                                                                
                                                                if ($selisih_balasan->d > 0) {
                                                                    echo $selisih_balasan->d . ' hari lalu';
                                                                } elseif ($selisih_balasan->h > 0) {
                                                                    echo $selisih_balasan->h . ' jam lalu';
                                                                } elseif ($selisih_balasan->i > 0) {
                                                                    echo $selisih_balasan->i . ' menit lalu';
                                                                } else {
                                                                    echo 'Baru saja';
                                                                }
                                                            ?>
                                                        </small>
                                                        <p><?php echo nl2br(htmlspecialchars($balasan->pesan)); ?></p>
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="empty-state">
                                    <div class="empty-state-icon">💬</div>
                                    <h3 class="empty-state-title">Belum ada diskusi</h3>
                                    <p class="empty-state-text">Jadilah yang pertama memulai diskusi di materi ini</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php else: ?>
                        <div class="empty-state">
                            <div class="empty-state-icon">💬</div>
                            <h3 class="empty-state-title">Pilih materi terlebih dahulu</h3>
                            <p class="empty-state-text">Silakan pilih materi dari sidebar untuk melihat diskusi</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk Pengumpulan Tugas -->
    <div id="modal-kumpul" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>📤 Kumpulkan Tugas</h2>
                <span class="close-modal" onclick="closeModal()">&times;</span>
            </div>
            <div class="modal-body">
                <form id="form-kumpul" method="POST" action="<?php echo site_url('siswa/modul/kumpul_tugas'); ?>" enctype="multipart/form-data">
                    <input type="hidden" name="tugas_id" id="modal-tugas-id">
                    <input type="hidden" name="materi_id" id="modal-materi-id">
                    
                    <div class="form-group">
                        <label for="file_tugas">Upload File Tugas (opsional)</label>
                        <input type="file" name="file_tugas" id="file_tugas" class="form-control">
                        <small>Format: zip, rar, pdf, doc, docx, jpg, jpeg, png. Maksimal 20MB.</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="link_pengumpulan">Link Pengumpulan (opsional)</label>
                        <input type="url" name="link_pengumpulan" id="link_pengumpulan" class="form-control" 
                               placeholder="Contoh: https://drive.google.com/...">
                    </div>
                    
                    <div class="form-group">
                        <label for="catatan">Catatan (opsional)</label>
                        <textarea name="catatan" id="catatan" class="form-control" rows="3" 
                                  placeholder="Tambahkan catatan untuk pengajar..."></textarea>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                        <button type="submit" class="btn-submit">Kirim Tugas</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // =============== FUNGSI UMUM ===============
        function switchTab(tabName) {
            // Update URL parameter
            const url = new URL(window.location.href);
            url.searchParams.set('tab', tabName);
            history.replaceState(null, '', url);
            
            // Hide all tabs
            document.querySelectorAll('.tab-content').forEach(tab => {
                tab.classList.remove('active');
            });
            
            // Show selected tab
            document.getElementById(`tab-${tabName}`).classList.add('active');
            
            // Update tab buttons
            document.querySelectorAll('.tab').forEach(button => {
                button.classList.remove('active');
            });
            document.querySelector(`.tab[onclick="switchTab('${tabName}')"]`).classList.add('active');
            
            // Initialize based on tab
            if (tabName === 'diskusi') {
                initializeDiskusiFunctions();
            }
        }

        // Check for tab parameter in URL
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const tabParam = urlParams.get('tab');
            
            if (tabParam === 'diskusi') {
                setTimeout(() => switchTab('diskusi'), 50);
            } else if (tabParam === 'tugas') {
                setTimeout(() => switchTab('tugas'), 50);
            }
            
            // Auto-hide flash notification
            const flashNotif = document.getElementById('flash-notification');
            if (flashNotif) {
                setTimeout(() => {
                    flashNotif.style.animation = 'slideOut 0.3s ease forwards';
                    setTimeout(() => flashNotif.remove(), 300);
                }, 5000);
            }
        });

        // =============== FUNGSI PROGRESS ===============
        function updateProgress(materi_id, checkbox) {
            const isChecked = checkbox.checked;
            const status = isChecked ? 'completed' : 'in_progress';
            
            fetch('<?php echo site_url("siswa/modul/update_progress_ajax"); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: `materi_id=${materi_id}&status=${status}`
            })
            .then(response => response.json())
            .then(data => {
                if (!data.success) {
                    checkbox.checked = !isChecked;
                    showNotification(data.message, 'error');
                } else {
                    showNotification('Progress berhasil diupdate', 'success');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                checkbox.checked = !isChecked;
                showNotification('Terjadi kesalahan', 'error');
            });
        }

        // =============== FUNGSI TUGAS ===============
        function showKumpulModal(tugasId, materiId) {
            document.getElementById('modal-tugas-id').value = tugasId;
            document.getElementById('modal-materi-id').value = materiId;
            document.getElementById('modal-kumpul').style.display = 'block';
        }

        function closeModal() {
            document.getElementById('modal-kumpul').style.display = 'none';
            document.getElementById('form-kumpul').reset();
        }

        function hapusPengumpulan(pengumpulanId) {
            if (confirm('Apakah Anda yakin ingin menghapus pengumpulan tugas ini?')) {
                fetch('<?php echo site_url("siswa/modul/hapus_pengumpulan_ajax/"); ?>' + pengumpulanId, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showNotification(data.message, 'success');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    } else {
                        showNotification(data.message, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showNotification('Terjadi kesalahan', 'error');
                });
            }
        }

        // =============== FUNGSI DISKUSI ===============
        function initializeDiskusiFunctions() {
            // Setup event listeners for reply forms
            document.querySelectorAll('.reply-btn').forEach(btn => {
                btn.onclick = function() {
                    const forumId = this.getAttribute('onclick').match(/toggleReplyForm\((\d+)\)/)[1];
                    toggleReplyForm(forumId);
                };
            });
        }

        function toggleReplyForm(forumId) {
            const replyForm = document.getElementById(`reply-form-${forumId}`);
            if (!replyForm) return;
            
            // Hide all other reply forms
            document.querySelectorAll('.reply-form').forEach(form => {
                if (form.id !== `reply-form-${forumId}`) {
                    form.style.display = 'none';
                }
            });
            
            // Toggle current reply form
            if (replyForm.style.display === 'block') {
                replyForm.style.display = 'none';
            } else {
                replyForm.style.display = 'block';
                const textarea = replyForm.querySelector('textarea');
                if (textarea) {
                    textarea.focus();
                }
            }
        }

        // =============== FUNGSI NOTIFIKASI ===============
        function showNotification(message, type = 'success') {
            // Remove existing notifications
            document.querySelectorAll('.flash-notification.custom').forEach(n => n.remove());
            
            const notification = document.createElement('div');
            notification.className = `flash-notification ${type} custom`;
            notification.textContent = message;
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 16px 24px;
                border-radius: 12px;
                box-shadow: 0 10px 40px rgba(0,0,0,0.2);
                z-index: 9999;
                animation: slideIn 0.3s ease;
            `;
            
            document.body.appendChild(notification);
            
            // Auto remove after 5 seconds
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease forwards';
                setTimeout(() => notification.remove(), 300);
            }, 5000);
        }

        // Tutup modal jika klik di luar
        window.onclick = function(event) {
            const modal = document.getElementById('modal-kumpul');
            if (event.target === modal) {
                closeModal();
            }
        };
    </script>
</body>
</html>