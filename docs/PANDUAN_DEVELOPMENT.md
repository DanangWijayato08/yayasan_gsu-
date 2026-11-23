# 💻 PANDUAN DEVELOPMENT - GSU Website

## Konvensi Kode

### Backend (PHP)
```php
// Classes: PascalCase
class ProgramController { }

// Methods: camelCase  
public function getProgramList() { }

// Variables: camelCase
$activePrograms = [];

// Constants: SCREAMING_SNAKE_CASE
const MAX_UPLOAD_SIZE = 5120;
```

### Frontend (JavaScript/Vue)
```javascript
// Components: PascalCase
export default { name: 'ProgramCard' }

// Methods & Variables: camelCase
const handleSubmit = () => { }
const isLoading = ref(false)

// Constants: SCREAMING_SNAKE_CASE
const API_TIMEOUT = 30000
```

## Struktur File

### Backend
```
app/Http/
├── Controllers/Api/
│   ├── Admin/          # Admin endpoints
│   └── Public/         # Public endpoints
├── Requests/           # Form validation
└── Middleware/         # Security & auth

app/Services/           # Business logic
app/Repositories/       # Data access
```

### Frontend
```
src/
├── components/         # Reusable UI
├── views/             # Page components
├── api/               # API services
├── stores/            # Pinia state
└── styles/            # Global CSS
```

## Coding Standards

### Form Validation (Backend)
```php
public function rules()
{
    return [
        'title' => 'required|string|max:255',
        'email' => 'required|email',
        'amount' => 'required|integer|min:1000',
    ];
}

public function messages()
{
    return [
        'title.required' => 'Judul wajib diisi',
        'email.email' => 'Email tidak valid',
    ];
}
```

### Service Layer (Backend)
```php
class ProgramService
{
    public function getActive()
    {
        return Program::where('is_active', true)->get();
    }

    public function create(array $data)
    {
        $program = Program::create($data);
        cache()->forget('programs:active');
        return $program;
    }
}
```

### Composables (Frontend)
```javascript
export function useProgram() {
    const programs = ref([])
    const isLoading = ref(false)
    
    const fetch = async () => {
        isLoading.value = true
        programs.value = await programService.getAll()
    }
    
    return { programs, isLoading, fetch }
}
```

## Git Workflow

### Branch Names
```
feature/deskripsi-fitur
fix/deskripsi-bug
refactor/deskripsi-refactoring
docs/deskripsi-dokumentasi
```

### Commit Messages
```
feat: tambah fitur dashboard
fix: perbaiki error upload
refactor: cleanup code
docs: update setup guide
```

## Testing

```bash
# Backend
php artisan test

# Frontend (saat sudah setup)
npm run test
```

## Checklist Code Review

- [ ] Input validation lengkap
- [ ] Error handling ada
- [ ] Security standar terpenuhi
- [ ] Database query optimal
- [ ] Components reusable
- [ ] Responsive design OK
- [ ] Documentation updated

---

**Version**: 1.0.0
