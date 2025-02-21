<x-bright-form :action="route('security.sessions')">
    <div class="page">
        <div class="page-header">
            <h1 class="page-title">
                <span ajax-total></span> Current Logged In Sessions
            </h1>
            <div class="page-options d-flex"></div>
        </div>
        <div class="page-body">
            <div class="card">
                <div class="table-responsive" style="min-height: 400px">
                    <table class="table card-table table-vcenter text-nowrap">
                        <thead>
                            <tr>
                                <th>ip</th>
                                <th>user agent</th>
                                <th>last activity</th>
                                <th>active</th>
                                <th>Logout</th>
                            </tr>
                        </thead>
                        <tbody fragment="content">
                            @fragment('content')
                                @foreach ($rows as $row)
                                    <tr>
                                        <td>{{ $row->ip_address }}</td>
                                        <td>{{ Illuminate\Support\Str::limit((string) $row->user_agent, 50) }}</td>
                                        <td>{{ datetime($row->last_activity) }}</td>
                                        <td>{{ $row->active }}</td>
                                        <td>
                                            @if (!$row->active)
                                                <a data-delete href="{{ route('security.delete', [$row->id]) }}"
                                                    title="Logout from this session" class="btn btn-secondary btn-sm">
                                                    <i class="ti ti-logout"></i>
                                                </a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @endfragment
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-bright-form>
