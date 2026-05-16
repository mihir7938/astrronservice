<div class="card card-primary">
    <div class="card-header">
        <h3 class="card-title">All Users</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th width="100">Action</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Action</th>
                        <th>Name</th>
                        <th>Mobile Number</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="text-center">
                                <a href="{{route('admin.users.edit', ['id' => $user->id])}}" class="btn btn-outline-primary btn-circle">
                                    <i class="fas fa-pen"></i>
                                </a>
                                @if($user->isUser() || $user->isService())
                                    <a href="{{route('admin.users.delete', ['id' => $user->id])}}" class="btn btn-outline-danger btn-circle">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                @endif
                            </td>
                            <td>{{$user->name}}</td>
                            <td>{{$user->phone}}</td>
                            <td>{{$user->email}}</td>
                            <td>{{$user->role->name}}</td>
                            <td>{{($user->status == 1) ? 'Active' : 'Not Active'}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>