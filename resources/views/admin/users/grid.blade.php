<div class="row form-row">
    @if (@count($user))
        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead class="table-head sticky-thead">
                    <tr class="text-center">
                        <th style="width: 14%">
                            氏名

                        </th>

                        <th style="width: 10%">ID</th>
                        <th style="width: 15%">学校名</th>
                        <th style="width: 15%">学科名</th>
                        <th style="width: 6%">年齢</th>
                        <th style="width: 10%">利用時間</th>
                        <th style="width: 10%">登録日</th>
                        <th style="width: 10%">有効期限</th>
                        <th class="text-center" style="width: 10%">ステータス</th>
                        @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                            <th class="text-center" style="width: 8%">アクション</th>
                        @endif
                    </tr>
                </thead>
                @foreach ($user as $key => $value)
                    <tbody class="table-body">
                        <tr class="text-center">
                            <td class="school-item text-left">
                                <p class="span-ellipsis ml-2" style="width: 130px">{{ $value->full_name }}</p>
                            </td>
                            <td class="school-item" style="width: 125px">{{ $value->student_code }}</td>
                            <td class="school-item">
                                <p class="span-ellipsis" style="max-width: 200px;">{{ $value->institution }}</p>
                            </td>
                            <td class="school-item">
                                <p class="span-ellipsis" style="max-width: 200px;">{{ $value->grade_name }}</p>
                            </td>
                            <td class="school-item">{{ $value->age }}</td>
                            @if ($value->usage_time == 0 || $value->usage_time == null)
                                <td class="school-item">0</td>
                            @else
                                <td class="school-item">
                                    {{ sprintf('%02d:%02d:%02d', $value->usage_time / 3600, ($value->usage_time / 60) % 60, $value->usage_time % 60) }}
                                </td>
                            @endif

                            <td class="school-item">
                                {{ \Carbon\Carbon::parse($value->created_at)->format('Y/m/d') }}
                            </td>
                            <td class="school-item">
                                {{ \Carbon\Carbon::parse($value->created_at)->format('Y/m/d') }}
                            </td>
                            <td class="school-item user-status">{{ $value->status == 1 ? 'アクティブ' : '非活性' }}</td>
                            <td class="school-item">
                                @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))
                                    <button class="refresh-user align-top" id="refresh-user"
                                        data-id="{{ $value->id }}"><i class="fa fa-refresh"
                                            aria-hidden="true"></i></button>
                                @endif
                                @if(in_array(Auth::user()->role_id, [\App\Consts::ADMIN, \App\Consts::MENTOR]))
                                    <button class="warning edit-user p-0" id="edit-user" data-id="{{ $value->id }}">
                                        <svg class="align-bottom" xmlns="http://www.w3.org/2000/svg" width="14"
                                            height="22" viewBox="1678.334 435.27 26.988 27.209">
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
                                        </svg>
                                    </button>
                                @endif
                                @if (in_array(Auth::user()->role_id, [\App\Consts::ADMIN]))

                                    <button class="danger delete-user align-top" id="delete"
                                        data-id="{{ $value->id }}"><i class="fa fa-trash-o"></i></button>
                                @endif
                            </td>
                        </tr>

                    </tbody>
                @endforeach
            </table>
        </div>
        <div id="pagination">
            {{ $user->links('pagination::bootstrap-4') }}
        </div>
    @else
        <div class="text-center col-12 font-weight-bold">
            <h2 class="text-light">データがありません。</h2>
        </div>
    @endif
</div>
