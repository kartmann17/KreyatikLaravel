@extends('adminlte::page')

@section('title', 'Dashboard')

@section('head_extra')
    @yield('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@stop

@section('content_header')
    <h1>@yield('page_title', 'Dashboard')</h1>
@stop

@section('content')
    @yield('content_body')
@stop

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Styles personnalisés */
        .card-dashboard {
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }
        .card-dashboard:hover {
            transform: translateY(-5px);
        }
        .stats-card {
            padding: 1.5rem;
            border-radius: 0.5rem;
            margin-bottom: 1rem;
        }
        .stats-icon {
            font-size: 2rem;
            opacity: 0.8;
        }
        .stats-number {
            font-size: 1.8rem;
            font-weight: bold;
        }
        .timer-display {
            font-family: monospace;
            font-size: 2rem;
            font-weight: bold;
            text-align: center;
            margin-bottom: 1rem;
        }
        .timer-controls {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
        }
        .project-card {
            border-radius: 0.5rem;
            overflow: hidden;
            margin-bottom: 1rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        .project-card-header {
            padding: 1rem;
            color: white;
        }
        .project-card-body {
            padding: 1rem;
        }
        .progress-bar {
            height: 0.5rem;
            border-radius: 1rem;
        }
        /* Styles pour les notifications */
        .notification-badge {
            position: absolute;
            top: 5px;
            right: 5px;
            font-size: 0.65rem;
            padding: 0.2rem 0.35rem;
        }
        .notification-item {
            border-left: 3px solid transparent;
            transition: background-color 0.2s;
        }
        .notification-item:hover {
            background-color: rgba(0,0,0,0.05);
        }
        .notification-item.unread {
            border-left-color: #007bff;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.7.0/dist/chart.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        // Vérifier les nouveaux messages au chargement
        document.addEventListener('DOMContentLoaded', function() {
            @if(auth()->check() && (auth()->user()->isAdmin() || auth()->user()->isStaff()))
            checkUnreadMessages();

            // Vérifier périodiquement les nouveaux messages (toutes les 5 minutes)
            setInterval(checkUnreadMessages, 5 * 60 * 1000);
            @endif
        });

        // Fonction pour vérifier les messages non lus
        function checkUnreadMessages() {
            axios.get('{{ route("admin.contact-messages.unread-count") }}')
                .then(function(response) {
                    const count = response.data.count;
                    updateNotificationBadge(count);
                })
                .catch(function(error) {
                    console.error('Erreur lors de la récupération des messages non lus:', error);
                    if (error.response && error.response.status === 404) {
                        console.log('Route non trouvée. Vérifiez que la route admin.contact-messages.unread-count est correctement définie.');
                    }
                });
        }

        // Mettre à jour le badge de notification
        function updateNotificationBadge(count) {
            const notificationBadge = document.getElementById('messages-notification-badge');
            const navLink = document.querySelector('a[href*="contact-messages"]');

            if (notificationBadge) {
                if (count > 0) {
                    notificationBadge.textContent = count;
                    notificationBadge.style.display = 'block';
                } else {
                    notificationBadge.style.display = 'none';
                }
            }

            // Mettre également à jour le badge dans le menu latéral si présent
            if (navLink) {
                const sidebarBadge = navLink.querySelector('.badge');
                if (sidebarBadge) {
                    if (count > 0) {
                        sidebarBadge.textContent = count;
                        sidebarBadge.style.display = 'inline-block';
                    } else {
                        sidebarBadge.style.display = 'none';
                    }
                } else if (count > 0) {
                    // Créer le badge s'il n'existe pas
                    const badge = document.createElement('span');
                    badge.className = 'badge badge-danger right';
                    badge.textContent = count;
                    navLink.appendChild(badge);
                }
            }
        }
    </script>

    @yield('custom_js')
@stop 