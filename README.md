# Laravel Multi-Tenant CRM Backend

A modular and scalable multi-tenant CRM backend built with Laravel.  
Designed with a clean architecture approach, event-driven workflows, and an API-first mindset.  
This project focuses on maintainability, extensibility, and clear separation of concerns.

---

## Features

### Multi-Tenancy
- Tenant-based data isolation
- Automatic tenant resolution via middleware
- Single codebase, multiple tenants

### Authentication
- Session-based authentication using httpOnly cookies
- Secure login and logout flow
- API requests authenticated via server-side sessions
- CSRF protection enabled
- No tokens exposed to frontend JavaScript

### CRM Core
- Client management
- Deals and sales pipelines
- Pipeline stages and transitions
- Basic activity tracking

### Messaging and Webhooks
- Unified abstraction for incoming messenger updates
- Adapter-based architecture (Telegram implemented)
- Centralized webhook entry point
- Idempotent event processing
- Queue-based asynchronous handling

### Architecture
- Clear separation between infrastructure and domain
- Provider-agnostic messaging layer
- Domain-level handlers and events
- Easy to extend with new messengers or integrations

---

## Webhook Processing Flow

Webhook Controller
↓
Messenger Adapter (Telegram, etc.)
↓
IncomingUpdate (Unified DTO)
↓
IncomingUpdateProcessor
↓
Handlers (Message / Status / Callback)
↓
Domain Events / Jobs

---

## Tech Stack

- Laravel 12
- PHP 8.3+
- MySQL
- Redis (queues and cache)
- Laravel Sail
- Inertia.js
- Laravel Sanctum (session and API authentication)

---

## Installation

```bash
git clone https://github.com/your-username/laravel-multitenant-crm.git
cd laravel-multitenant-crm

cp .env.example .env
./vendor/bin/sail up -d

sail artisan key:generate
sail artisan migrate --seed
```

## Authentication Flow

- User login creates a secure server-side session
- Authentication is handled via httpOnly cookies
- The same session is used for API requests
- No access tokens are exposed to the frontend
- CSRF protection is enabled

---

## Webhooks

### Endpoint

POST /api/webhook/{provider}

### Supported Providers

- Telegram

### Adding a New Provider

1. Create a new adapter implementing `MessengerAdapter`
2. Register the adapter in the update processor
3. No domain changes are required

---

## Testing

Testing is currently a work in progress.

**Planned:**
- Feature tests
- Adapter tests
- Handler and domain tests

---

## Roadmap

- WhatsApp adapter
- Facebook Messenger adapter
- Webhook retry and dead-letter queue
- Fine-grained permissions system
- Full test coverage

---

## Author

Built by **Oleksii Aivazian**  
Backend Engineer — Laravel, APIs, Architecture
