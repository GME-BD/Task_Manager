<div class="col-md-6 col-lg-4 mb-3">
    <div class="card h-100 border-0 shadow-sm reminder-card 
                {{ $reminder->is_overdue ? 'border-danger' : '' }}
                {{ $reminder->is_completed ? 'border-success' : '' }}">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-start mb-2">
                <h6 class="card-title mb-0 {{ $reminder->is_completed ? 'text-decoration-line-through text-muted' : '' }}">
                    {{ $reminder->title }}
                </h6>
                <span class="badge bg-{{ $reminder->priority_color }}">
                    {{ ucfirst($reminder->priority) }}
                </span>
            </div>
            
            @if($reminder->description)
                <p class="card-text text-muted small mb-2">
                    {{ Str::limit($reminder->description, 100) }}
                </p>
            @endif
            
            <div class="d-flex justify-content-between align-items-center text-muted small mb-3">
                <div>
                    <i class="bi bi-calendar3"></i> {{ $reminder->formatted_date }}
                </div>
                @if($reminder->time)
                    <div>
                        <i class="bi bi-clock"></i> {{ $reminder->formatted_time }}
                    </div>
                @endif
            </div>
            
            <div class="d-flex gap-2">
                @if(!$reminder->is_completed)
                    <form action="{{ route('reminders.mark-completed', $reminder->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-success btn-sm">
                            <i class="bi bi-check-lg"></i> Complete
                        </button>
                    </form>
                @else
                    <form action="{{ route('reminders.mark-incomplete', $reminder->id) }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-warning btn-sm">
                            <i class="bi bi-arrow-counterclockwise"></i> Reopen
                        </button>
                    </form>
                @endif
                
                <a href="{{ route('reminders.edit', $reminder->id) }}" class="btn btn-outline-primary btn-sm">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                
                <form action="{{ route('reminders.destroy', $reminder->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                            onclick="return confirm('Are you sure you want to delete this reminder?')">
                        <i class="bi bi-trash"></i> Delete
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>