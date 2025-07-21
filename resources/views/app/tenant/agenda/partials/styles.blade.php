{{-- resources/views/app/tenant/agenda/partials/styles.blade.php --}}

<!-- Bootstrap CSS (if not already included in your main layout) -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- Remix Icon CSS -->
<link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">

<!-- FullCalendar Core CSS (already included in scripts.blade.php) -->
<!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet"> -->

<!-- Custom Calendar Styles -->
<style>
    .calendar-container {
        height: calc(100vh - 120px);
        min-height: 600px;
    }

    .fc .fc-toolbar-title {
        font-size: 1.5em;
        font-weight: 500;
    }

    .fc .fc-button {
        padding: 0.3em 0.8em;
    }

    .fc-event {
        cursor: pointer;
        border-radius: 3px;
        padding: 3px 5px;
    }

    .employee-list {
        max-height: 400px;
        overflow-y: auto;
    }

    .employee-item {
        display: flex;
        align-items: center;
        padding: 8px 0;
        cursor: pointer;
    }

    .employee-item img {
        width: 32px;
        height: 32px;
        border-radius: 50%;
        margin-right: 10px;
        object-fit: cover;
    }

    .employee-item.active {
        font-weight: bold;
    }

    .filter-badge {
        cursor: pointer;
        transition: all 0.2s ease;
        opacity: 0.6;
    }

    .filter-badge.active {
        opacity: 1;
        transform: scale(1.05);
    }

    .event-card {
        cursor: grab;
        margin-bottom: 10px;
        border-radius: 4px;
    }

    .event-card:active {
        cursor: grabbing;
    }

    #toast-container {
        position: fixed;
        bottom: 20px;
        right: 20px;
        z-index: 1060;
    }

    .employee-selector-container {
        max-height: 300px;
        overflow-y: auto;
    }

    /* Calendar and event styling */
    .fc-event {
        cursor: pointer;
        border-radius: 4px;
        padding: 4px;
        margin-bottom: 2px;
        transition: all 0.2s ease;
    }

    .fc-event:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
    }

    .fc-toolbar-chunk {
        display: flex;
        gap: 8px;
        align-items: center;
    }

    @media (max-width: 768px) {
        .fc .fc-toolbar {
            flex-direction: column;
            gap: 1rem;
        }

        .fc-header-toolbar {
            padding: 10px !important;
        }
    }

    .event-status {
        display: inline-block;
        width: 8px;
        height: 8px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .event-priority {
        font-size: 0.75rem;
        padding: 2px 6px;
        border-radius: 10px;
    }

    /* Priority styling */
    .event-priority-urgent {
        background-color: #dc3545;
        color: white;
    }

    .event-priority-high {
        background-color: #fd7e14;
        color: white;
    }

    .event-priority-medium {
        background-color: #ffc107;
        color: #212529;
    }

    .event-priority-low {
        background-color: #20c997;
        color: white;
    }

    .fc-event-title {
        display: flex;
        align-items: center;
        gap: 4px;
    }

    /* Mini event cards for daily view */
    .event-card {
        border-left: 4px solid #0d6efd;
        transition: all 0.2s ease;
        margin-bottom: 10px;
        position: relative;
        cursor: move;
    }

    .event-card:hover {
        transform: translateX(3px);
    }

    /* Event card types */
    .event-card.inspection {
        border-left-color: #0d6efd;
    }

    .event-card.task {
        border-left-color: #ffc107;
    }

    .event-card.meeting {
        border-left-color: #6f42c1;
    }

    .event-card.deadline {
        border-left-color: #dc3545;
    }

    /* Calendar filter badges */
    .filter-badge {
        cursor: pointer;
        padding: 3px 8px;
        margin-right: 5px;
        border-radius: 15px;
        font-size: 0.8rem;
        user-select: none;
    }

    .filter-badge-inspection {
        background-color: rgba(13, 110, 253, 0.2);
        color: #0d6efd;
    }

    .filter-badge-task {
        background-color: rgba(255, 193, 7, 0.2);
        color: #856404;
    }

    .filter-badge-meeting {
        background-color: rgba(111, 66, 193, 0.2);
        color: #6f42c1;
    }

    .filter-badge-deadline {
        background-color: rgba(220, 53, 69, 0.2);
        color: #dc3545;
    }

    .filter-badge.active {
        background-color: #0d6efd;
        color: white;
    }

    /* Status colors */
    .status-dot {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 5px;
    }

    .status-planned {
        background-color: #6c757d;
    }

    .status-in-progress {
        background-color: #0d6efd;
    }

    .status-completed {
        background-color: #198754;
    }

    .status-cancelled {
        background-color: #dc3545;
    }

    /* Team member avatars */
    .avatar-group {
        display: flex;
    }

    .avatar-group .avatar {
        width: 30px;
        height: 30px;
        border-radius: 50%;
        overflow: hidden;
        border: 2px solid #fff;
        margin-left: -8px;
    }

    .avatar-group .avatar:first-child {
        margin-left: 0;
    }

    .avatar-group .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    /* Resource timeline */
    .resource-header {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 5px;
    }

    .resource-avatar {
        width: 24px;
        height: 24px;
        border-radius: 50%;
    }

    /* Drag and drop styling */
    .event-card.fc-event-dragging {
        opacity: 0.7;
        transform: scale(0.95);
    }

    .event-card:hover .drag-handle {
        opacity: 1;
    }

    .drag-handle {
        opacity: 0.3;
        transition: opacity 0.2s;
        z-index: 10;
    }

    /* Toast notification container */
    #toast-container {
        z-index: 1070;
    }

    /* Add visual feedback when dragging */
    .fc-event.fc-event-dragging {
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .sidebar-draggable .event-card {
        border-left-width: 4px;
        border-radius: 0.25rem;
        margin-bottom: 0.5rem;
    }
</style>
