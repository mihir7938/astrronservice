<div class="card">
    <div class="card-header">
        <h3 class="card-title">All Complains</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered" id="dataTableSupport" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Action</th>
                        <th>Complain No</th>
                        <th>Date</th>
                        <th>Company Name</th>
                        <th>Mobile Number</th>
                        <th>Contact Person</th>
                        <th>Issue</th>
                        <th>User</th>
                        <th>Assign</th>
                        <th>Solution Stage</th>
                        <th>Estimation Cost</th>
                        <th>Issue Products</th>
                        <th>Receive Products</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tfoot>
                    <tr>
                        <th>Action</th>
                        <th>Complain No</th>
                        <th>Date</th>
                        <th>Company Name</th>
                        <th>Mobile Number</th>
                        <th>Contact Person</th>
                        <th>Issue</th>
                        <th>User</th>
                        <th>Assign</th>
                        <th>Solution Stage</th>
                        <th>Estimation Cost</th>
                        <th>Issue Products</th>
                        <th>Receive Products</th>
                        <th>Message</th>
                    </tr>
                </tfoot>
                <tbody>
                    @foreach($complains as $complain)
                        <tr>
                            <td style="width: 120px;text-align: center;">
                                <a href="{{route('admin.complains.edit', ['id' => $complain->id])}}" class="btn btn-outline-primary btn-circle">
                                    <i class="fas fa-pen"></i>
                                </a>
                                <a href="{{route('admin.complains.delete', ['id' => $complain->id])}}" class="btn btn-outline-danger btn-circle">
                                    <i class="fas fa-trash"></i>
                                </a>
                                <a href="{{route('admin.complains.log', ['id' => $complain->id])}}" class="btn btn-outline-dark btn-circle">
                                    <i class="fas fa-history"></i>
                                </a>
                            </td>
                            <td>{{$complain->id}}</td>
                            <td>{{Carbon\Carbon::parse($complain->complain_date)->format('d-m-Y')}}</td>
                            <td>{{$complain->company_name}}</td>
                            <td>{{$complain->contact_number}}</td>
                            <td>{{$complain->contact_name}}</td>
                            <td>{{$complain->issue->name}}</td>
                            <td>{{$complain->user->name}}</td>
                            <td>{{$complain->assign ? $complain->assign->name : ''}}</td>
                            <td>{{$complain->solution ? $complain->solution->name : ''}}</td>
                            <td>{{$complain->estimation_cost}}</td>
                            <td>
                                @if($complain->issueProducts->count() > 0)
                                    {{ $complain->issueProducts->pluck('product.name')->filter()->implode(', ') }}
                                @endif
                            </td>
                            <td>
                                @if($complain->receiveProducts->count() > 0)
                                    {{ $complain->receiveProducts->pluck('product.name')->filter()->implode(', ') }}
                                @endif
                            </td>
                            <td>{{$complain->message}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>