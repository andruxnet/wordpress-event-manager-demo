# WordPress Code Challenge: Event Management Plugin

## Overview
Create a WordPress plugin that manages events with modern PHP practices, focusing on clean architecture, dependency injection, and comprehensive testing.

## Requirements

### Core Functionality
1. **Event Management**
    - Create, read, update, delete events
    - Events have: title, description, date/time, location, capacity, status
    - Custom post type with meta fields
    - Admin interface for managing events

2. **Public Features**
    - Display upcoming events on frontend
    - Event registration system (simple email collection)
    - Registration capacity limits
    - Shortcode support `[andrux_event_list]` and `[andrux_event_registration id="123"]`

### Technical Requirements

#### 1. Modern PHP Patterns
- **Namespace everything** under `Andrux\EventManager\`
- **Dependency Injection Container** (use PHP-DI or create simple one)
- **Repository Pattern** for data access
- **Service Layer** for business logic
- **Factory Pattern** for object creation
- **PSR-4 Autoloading** with Composer

#### 2. Architecture Structure
```
plugin-root/
├── composer.json
├── event-manager.php (main plugin file)
├── src/
│   ├── Container/
│   │   └── ServiceContainer.php
│   ├── Models/
│   │   ├── Event.php
│   │   └── Registration.php
│   ├── Repositories/
│   │   ├── EventRepository.php
│   │   └── RegistrationRepository.php
│   ├── Services/
│   │   ├── EventService.php
│   │   └── RegistrationService.php
│   ├── Controllers/
│   │   ├── AdminController.php
│   │   └── PublicController.php
│   ├── Factories/
│   │   └── EventFactory.php
│   └── Plugin.php (main plugin class)
├── templates/
│   ├── admin/
│   └── public/
├── assets/
│   ├── css/
│   └── js/
└── tests/
    ├── Unit/
    └── Integration/
```

#### 3. Specific Implementations

**Event Model Example:**
```php
namespace Andrux\EventManager\Models;

class Event {
    private int $id;
    private string $title;
    private string $description;
    private \DateTime $eventDate;
    private string $location;
    private int $capacity;
    private string $status; // 'active', 'cancelled', 'full'
    
    // Constructor, getters, setters with proper type hints
    // Validation methods
    // toArray() method for serialization
}
```

**Repository Pattern:**
```php
interface EventRepositoryInterface {
    public function findById(int $id): ?Event;
    public function findUpcoming(int $limit = 10): array;
    public function save(Event $event): bool;
    public function delete(int $id): bool;
}
```

**Service Layer with DI:**
```php
class EventService {
    public function __construct(
        private EventRepositoryInterface $eventRepository,
        private RegistrationRepositoryInterface $registrationRepository
    ) {}
    
    public function registerForEvent(int $eventId, string $email): bool {
        // Business logic here
    }
}
```

#### 4. WordPress Integration
- **Hooks & Filters**: Proper use of WordPress hooks
- **Custom Post Types**: Register event post type
- **Meta Boxes**: Custom fields in admin
- **REST API**: Extend WP REST API with custom endpoints
- **Shortcodes**: Implement the required shortcodes
- **Admin Pages**: Custom admin interface
- **Activation/Deactivation**: Database setup/cleanup

#### 5. Unit Testing (PHPUnit)
- Test all service classes
- Test repositories (use WordPress test framework)
- Mock dependencies properly
- Aim for 80%+ code coverage
- Include both unit and integration tests

**Example Test:**
```php
class EventServiceTest extends TestCase {
    public function testRegisterForEventWithValidData() {
        // Mock dependencies
        // Test registration logic
        // Assert expected outcomes
    }
}
```

### Bonus Points
1. **Docker Setup**: Include docker-compose.yml for local development
2. **CI/CD**: GitHub Actions for running tests
3. **Code Quality**: PHPStan/Psalm static analysis
4. **Documentation**: PHPDoc comments, README with setup instructions
5. **Validation**: Implement proper input validation and sanitization
6. **Error Handling**: Custom exceptions and proper error handling
7. **Caching**: Implement basic caching for event queries
