<div class="row form-row">
    @if (@count($school))
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-head position-sticky" style="top: 0">
                    <tr>
                        <th class="text-center" style="width: 8%">
                            No.
                        </th>

                        <th style="width: 20%">学校名</th>
                        <th style="width: 30%">学科名</th>
                        <th style="width: 10%">生徒数</th>
                        <th style="width: 10%">登録日</th>
                        <th class="text-center" style="width: 8%">編集</th>
                        <th class="text-center" style="width: 8%">削除</th>
                    </tr>
                </thead>
                @foreach ($school as $key => $value)
                    <?php $i = ($school->currentPage() - 1) * $school->perPage() + $loop->index + 1; ?>
                    <tbody class="table-body {{ $i % 2 !== 0 ? 'bg-white' : 'bg-transperant' }}" id="table-body"
                        data-id="{{ $value->id }}">

                        <tr>
                            <td class="school-item text-center" scope="row">{{ $i }}</td>

                            <td class="school-item">
                                <div class="d-flex">
                                    @if ($value->grade->isEmpty())
                                        <p class="span-ellipsis" style="width: 230px; margin-left: 23px;">
                                            {{ $value->name }}</p>
                                    @else
                                        <span class="dropdown-details" data-id="{{ $value->id }}"
                                            style="font-size: 20px; line-height: 1">&blacktriangleright;</span>
                                        <p class="span-ellipsis" style="width: 230px;">{{ $value->name }}</p>
                                    @endif
                                </div>
                            </td>

                            <td class="school-item">

                            </td>

                            <td class="school-item">{{ $value->sum_student }}</td>

                            <td class="school-item">{{ \Carbon\Carbon::parse($value->created_at)->format('Y/m/d') }}
                            </td>
                            <td class="school-item text-center">

                                <a href="{{ url('admin/school/' . $value->id . '/edit') }}" class="warning edit-school"
                                    id="edit-school" data-id="{{ $value->id }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="22"
                                        viewBox="1678.334 435.27 26.988 27.209">
                                        <g data-name="edit">
                                            <path
                                                d="m1688.833 456.875 1.94-1.94h-7.19a1.141 1.141 0 1 1 0-2.283h9.473l5.86-5.861c.252-.251.53-.459.826-.623v-7.8a3.098 3.098 0 0 0-3.098-3.098h-15.213a3.098 3.098 0 0 0-3.097 3.098v18.196a3.098 3.098 0 0 0 3.097 3.097h6.56l.842-2.786Zm-6.391-15.678c0-.633.513-1.141 1.141-1.141h10.91a1.141 1.141 0 0 1 0 2.283h-10.91a1.141 1.141 0 0 1-1.141-1.142Zm0 6.299c0-.634.513-1.142 1.141-1.142h10.91a1.141 1.141 0 0 1 0 2.283h-10.91a1.145 1.145 0 0 1-1.141-1.141Z"
                                                fill="#8ddae7" fill-rule="evenodd" data-name="パス 26" />
                                            <g data-name="グループ 13">
                                                <g data-name="グループ 11">
                                                    <path
                                                        d="m1695.945 460.18-3.277-3.278 9.008-9.008a1.266 1.266 0 0 1 1.786 0l1.491 1.492a1.266 1.266 0 0 1 0 1.786l-9.008 9.008Z"
                                                        fill="#8ddae7" fill-rule="evenodd" data-name="パス 27" />
                                                </g>
                                                <g data-name="グループ 12">
                                                    <path d="m1694.552 461.485-4.228.995.994-4.228 3.234 3.233Z"
                                                        fill="#8ddae7" data-name="パス 28" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg></a>
                            </td>
                            @if ($value->sum_student == 0 || $value->sum_student == null)
                                <td class="school-item text-center">
                                    <button class="danger delete-school" id="delete"
                                        data-id="{{ $value->id }}"><i class="fa fa-trash-o"></i></button>
                                </td>
                            @else
                                <td>
                                    <i class="fa fa-trash-o" style="cursor: not-allowed; color: #c1c1c1"></i>
                                </td>
                            @endif
                        </tr>

                    <tbody class="table-body hidden {{ $i % 2 !== 0 ? 'bg-white' : 'bg-transperant' }}" id="details"
                        data-id="{{ $value->id }}">

                    </tbody>
                    </tbody>
                @endforeach
            </table>
        </div>
        <div id="pagination">
            {{ $school->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="text-center col-12 font-weight-bold">
            <h2 class="text-light">データがありません。</h2>
        </div>
    @endif

</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
<script type="text/javascript"></script>
