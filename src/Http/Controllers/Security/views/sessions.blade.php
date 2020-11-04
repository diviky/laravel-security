@view('ajax') @form('start')

<div class="page-header">
    <h1 class="page-title">
        <span ajax-total></span> Current Logged In Sessions
    </h1>
</div>

<div class="card">
    <div class="table-responsive" style="min-height: 400px">
        <table class="table card-table table-vcenter text-nowrap">
            <thead>
                <tr>
                    <th>ip</th>
                    <th>user agent</th>
                    <th>last activity</th>
                    <th>Logout</th>
                </tr>
            </thead>
            <tbody ajax-content >
                @endview @foreach($rows as $row)
                <tr>
                    <td>{{ $row->ip_address }}</td>
                    <td>{{ str_limit($row->user_agent, 100) }}</td>
                    <td>{{ datetime($row->last_activity) }}</td>
                    <td>
                        @if(!$row->active)
                        <a data-delete href="{{ url('security/sessions/delete/'.$row->id) }}" title="Logout from this session"
                            class="btn btn-secondary btn-sm">
                            <i class="fe fe-log-out"></i>
                        </a>
                        @endif
                    </td>
                </tr>
                @endforeach
                @view('ajax')
            </tbody>
        </table>
    </div>
</div>

@form('end') @endview
