@view('ajax') @form('start')

<div class="page-header">
    <h1 class="page-title">
        <span ajax-total>{{ $rows->total() }}</span> Login history
    </h1>
    <div class="page-options d-flex">
        <div class="input-icon ml-2 mr-1">
            <span class="input-icon-addon">
                <i class="fe fe-search"></i>
            </span>
            <input type="text" class="form-control w-10" data-dateranges name="datetime[created_at]"
                   placeholder="Choose Date">
        </div>
        <div class="input-icon ml-2 mr-1">
            <input type="text" class="form-control w-10" name="lfilter[ips]" placeholder="Search by ip">
        </div>
        <div class="input-icon ml-2 mr-1">
            <button type="submit" data-task="" class="btn btn-primary btn-block"> Search</button>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-responsive" style="min-height: 400px">
        <table class="table card-table table-vcenter text-nowrap">
            <thead>
            <tr>
                <th class="w-1">#</th>
                <th>ip_address</th>
                <th>Region</th>
                <th>Browser</th>
                <th>Device</th>
                <th>Login At</th>
            </tr>
            </thead>
            <tbody ajax-content >
            @endview @foreach($rows as $row)
                <tr>
                    <td class="text-center">{{ $row->serial }}</td>
                    <td>
                        <div class="text-bold">{{ $row->ips }}</div>
                        <div class="text-muted">{{ $row->country }}</div>
                    </td>
                    <td>
                        <div class="text-bold">{{ $row->region }}</div>
                        <div class="text-muted">{{ $row->city }}</div>
                    </td>
                    <td>
                        <div class="text-bold">{{ $row->device }}</div>
                        <div class="text-muted">{{ $row->browser }}</div>
                    </td>
                    <td>
                        <div class="text-bold">{{ $row->os }}</div>
                        <div class="text-muted">{{ $row->brand }}</div>
                    </td>
                    <td>
                        <div>{{ datetime($row->created_at) }}</div>
                    </td>
                </tr>
            @endforeach
            <tr>
                <td colspan="10" align="center">
                    <div class="d-flex" class="ac-load-more-remove">
                        {{ $rows->links() }}
                    </div>
                </td>
            </tr>
            @view('ajax')
            </tbody>
        </table>
    </div>
</div>

@form('end') @endview
