# Modern PHP Framework Attributes Reference Guide

---

## 1. Laravel Framework Attributes

### 📦 Eloquent Models (`Illuminate\Database\Eloquent\Attributes\*`)
These replace traditional class properties at the top of your Eloquent model definitions:
* **`#[Table('name')]`** – Defines the precise database table name.
* **`#[Fillable([...])]`** – Lists columns permitted for mass-assignment.
* **`#[Guarded([...])]`** – Lists explicitly restricted columns from mass-assignment.
* **`#[Hidden([...])]`** – Masks specific keys during array or JSON serialization.
* **`#[Visible([...])]`** – Restricts array/JSON serialization solely to these keys.
* **`#[Appends([...])]`** – Appends custom virtual model accessors into JSON payloads.
* **`#[Connection('name')]`** – Overrides the model's targeted database driver connection.
* **`#[ObservedBy(UserObserver::class)]`** – Instantly registers a model observer class lifecycle listener.
* **`#[ScopedBy(GlobalScope::class)]`** – Applies a cleaner global query restriction.
* **`#[Scope('name')]`** – Registers localized model query scopes natively.
* **`#[UseEloquentBuilder(CustomBuilder::class)]`** – Directs Laravel to load a dedicated, custom query builder.
* **`#[UseFactory(UserFactory::class)]`** – Maps a dedicated factory instantiation strategy.
* **`#[UsePolicy(UserPolicy::class)]`** – Explicitly maps standard authorization policies.
* **`#[UseResource(UserResource::class)]`** – Links standard API resource formatting blocks.
* **`#[UseResourceCollection(UserCollection::class)]`** – Links collection resource wrappers.
* **`#[Boot]`** – Automatically executes custom internal model instantiation boot procedures.
* **`#[Initialize]`** – Instructs the model to execute local constructor configuration code.
* **`#[WithoutIncrementing]`** – Signals that the database table primary key is non-incrementing.
* **`#[WithoutTimestamps]`** – Completely deactivates native database table timestamp updates.

### ⚙️ Artisan Console Commands (`Illuminate\Console\Attributes\*`)
These attributes define structural metadata for commands, replacing old class properties:
* **`#[Signature('app:run-jobs')]`** – Establishes the string syntax parameters used to run commands.
* **`#[Description('Run scheduled scripts')]`** – Adds terminal description text.
* **`#[Usage('app:run-jobs --force')]`** – Appends actionable terminal example blocks for user assistance.
* **`#[Help('Detailed documentation text')]`** – Defines explicit blocks printed during the terminal `--help` command trace.
* **`#[Hidden]`** – Conceals the designated custom command from the default `php artisan` listing.

### 🎛️ Routing & Http Controller Actions (`Illuminate\Routing\Attributes\*` / `Illuminate\Foundation\*`)
These attributes attach configurations directly to your HTTP request lifecycles:
* **`#[Middleware('auth')]`** – Registers targeted middleware blocks directly above classes or action routes.
* **`#[Authorize('update', 'post')]`** – Runs a gateway authorization verification sweep before executing code blocks.
* **`#[FailOnUnknownFields]`** – Triggers strict verification on `FormRequest` inputs, failing if unmapped client data is supplied.

### 🔄 Queued Background Jobs (`Illuminate\Queue\Attributes\*`)
These replace traditional class properties on background jobs, listeners, and mailables:
* **`#[Connection('redis')]`** – Declares the queue connection handler.
* **`#[Queue('high-priority')]`** – Routes background data to dedicated named pipeline processing streams.
* **`#[Tries(5)]`** – Defines how many times a failed queue job can retry processing.
* **`#[Timeout(120)]`** – Imposes rigid execution time ceilings on individual background jobs.
* **`#[Backoff()]`** – Dictates standard delay time steps between sequential job retries.
* **`#[Delay(30)]`** – Dynamically holds execution processes back for designated seconds.
* **`#[DebounceFor(30, maxWait: 120)]`** – Delays and limits duplicate asynchronous event dispatches.
* **`#[FailOnTimeout]`** – Forces background queue cycles to instantly mark as failed if execution limits expire.
* **`#[MaxExceptions(3)]`** – Caps the absolute unhandled exception thresholds allowed before failing.
* **`#[DeleteWhenMissingModels]`** – Automatically discards pending jobs safely if an attached model record was deleted.

### 🧠 Dependency Injection (`Illuminate\Container\Attributes\*`)
Used to resolve parameters injected into your controller actions or constructor methods:
* **`#[CurrentUser]`** – Dynamically binds and hydrates the authenticated user object.
* **`#[Config('app.timezone')]`** – Directly injects values straight out of project configuration arrays.
* **`#[Auth('admin')]`** – Restricts and binds parameters evaluated via specific authentication guard instances.
* **`#[Bind(ClientInterface::class)]`** – Injects specific container interface concrete bindings directly.

---

## 2. Symfony Framework Attributes

### 🌐 Routing & HTTP
* **`#[Route('/api/posts', name: 'api_posts', methods: ['GET'])]`** – Full HTTP endpoint definitions directly above controllers.
* **`#[Cache(public: true, maxage: 3600)]`** – Handles client-side or gateway HTTP caching configuration context.

### 💉 Dependency Injection
* **`#[AsController]`** – Identifies a class to Symfony's compiler container map as an action routing point.
* **`#[AsEventListener(event: 'kernel.exception')]`** – Binds service methods to core application system lifecycle hooks.
* **`#[MapQueryParameter]`** / **`#[MapRequestPayload]`** – Parses incoming request arrays into clean data transfer objects.
* **`#[Autowired]`** / **`#[Target('logger.security')]`** – Targets exact system resource implementations explicitly.

### 🛡️ Database & Validation (Doctrine ORM)
* **`#[ORM\Entity(repositoryClass: PostRepository::class)]`** – Identifies a database tracking model class framework layout.
* **`#[ORM\Column(type: 'string', length: 255, unique: true)]`** – Declares inline schema database rules.
* **`#[Assert\NotBlank]`** / **`#[Assert\Email]`** – Enforces input string data safety validations.

---

## 3. Spiral Framework & RoadRunner Attributes

* **`#[AsJob('queue_name')]`** – Declares background queue worker classes natively.
* **`#[GuardInterceptors]`** – Controls authorization gates on execution targets.
* **`#[Compute]`** – Manages inline background data hydration schemes.
