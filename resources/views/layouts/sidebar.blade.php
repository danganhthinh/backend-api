<div class="main-menu menu-fixed menu-light menu-accordion menu-shadow" data-scroll-to-active="true">
    <div class="main-menu-content">
        <!-- Sidebar -->

        <ul class="nav navbar-nav side-nav" id="sidebar">
            <li class="navigation" id="navigation">
                <ul class="menu" id='menu-sidebar'>
                    @if(in_array(Auth::user()->role_id, [\App\Consts::ADMIN]) )
                    <li class="{{  \Request::segment(2) == 'school' || \Request::segment(2) == 'group' ? 'active' : ''  }}">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="88.94 159 22.355 25.47">
                                <path d="M108.061 159H92.175a3.235 3.235 0 0 0-3.235 3.234v19.001a3.235 3.235 0 0 0 3.235 3.235h15.886a3.235 3.235 0 0 0 3.235-3.235v-19A3.235 3.235 0 0 0 108.06 159Zm-1.055 19.343c0 .662-.536 1.193-1.192 1.193H94.422a1.192 1.192 0 1 1 0-2.384h11.392a1.188 1.188 0 0 1 1.192 1.191Zm0-6.577c0 .662-.536 1.192-1.192 1.192H94.422a1.192 1.192 0 1 1 0-2.384h11.392c.656 0 1.192.53 1.192 1.192Zm0-6.577c0 .662-.536 1.192-1.192 1.192H94.422a1.192 1.192 0 1 1 0-2.384h11.392c.656 0 1.192.53 1.192 1.192Z" data-name="manage"/>
                            </svg>&nbsp;学校マスタ管理
                        </a>
                        <ul class="dropdown-content position-absolute">
                            <li class="{{  \Request::segment(2) == 'school' && \Request::segment(3) == '' ? 'active' : ''  }}" style="padding: 15px 25px 0 25px;"><a href="{{ url('admin/school') }}">学校管理</a></li>
                            <li class="{{  \Request::segment(2) == 'school' && \Request::segment(3) == 'create' ? 'active' : ''  }}" style="padding: 15px 25px 0 25px;"><a href="{{ url('admin/school/create') }}">学校マスタ登録</a></li>
                            <li class="{{  \Request::segment(2) == 'group' && \Request::segment(3) == '' ? 'active' : ''  }}" style="padding: 15px 25px 0 25px;"><a href="{{ url('admin/group') }}">団体管理</a></li>
                            <li class="{{  \Request::segment(2) == 'group' && \Request::segment(3) == 'create' ? 'active' : ''  }}"style="padding: 15px 25px;"><a href="{{ url('admin/group/create') }}">団体マスタ登録 </a></li>    
                        </ul>
                    </li>
                    <li class="{{ \Request::segment(2) == 'mentor' ? 'active' : '' }}">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14px" height="14px" viewBox="0 0 14 16">
                                <g id="surface1">
                                    <path d="M 12.144531 0 L 2.054688 0 C 0.921875 0 0 0.921875 0 2.054688 L 0 14.121094 C 0 15.253906 0.921875 16.175781 2.054688 16.175781 L 12.144531 16.175781 C 13.277344 16.175781 14.195312 15.253906 14.195312 14.121094 L 14.195312 2.054688 C 14.195312 0.921875 13.277344 0 12.144531 0 Z M 2.480469 13.125 L 3.140625 10.320312 L 5.285156 12.464844 Z M 12.183594 5.625 L 6.207031 11.601562 L 4.03125 9.425781 L 10.007812 3.453125 C 10.335938 3.128906 10.867188 3.128906 11.195312 3.453125 L 12.183594 4.441406 C 12.507812 4.769531 12.507812 5.296875 12.183594 5.625 Z M 12.183594 5.625 "/>
                                </g>
                            </svg>&nbsp;管理者登録
                        </a>
                        <ul class="dropdown-content position-absolute">
                            <li class="{{  \Request::segment(2) == 'mentor' && \Request::segment(3) == '' ? 'active' : ''  }}" style="padding: 10px 25px 0 25px;"><a href="{{ url('admin/mentor') }}">管理者管理</a></li>
                            <li class="{{  \Request::segment(2) == 'mentor' && \Request::segment(3) == 'create' ? 'active' : ''  }}" style="padding: 10px 25px"><a href="{{ url('admin/mentor/create') }}">管理者登録</a></li>
   
                        </ul>
                    </li>
                    @endif
                    <li class="{{ \Request::segment(2) == 'user' ? 'active' : '' }}">
                        <a href="{{ url('admin/user') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="90 296 21.296 21.287">
                                <g data-name="人物のアイコン素材 その3"><path d="M100.647 307.747a5.874 5.874 0 1 0 .001-11.747 5.874 5.874 0 0 0 0 11.747Z" fill-rule="evenodd" data-name="パス 14"/>
                                    <path d="M110.953 315.47c-1.245-3.826-5.813-5.91-10.305-5.91-4.493 0-9.06 2.084-10.305 5.91a7.179 7.179 0 0 0-.343 1.817h21.296a7.202 7.202 0 0 0-.343-1.817Z" fill-rule="evenodd" data-name="パス 15"/>
                                </g>
                            </svg>&nbsp;ユーザー登録
                        </a>
                    </li>
                    <li class="{{ \Request::segment(2) == 'question' ? 'active' : '' }} position-relative">
                        <a href="#">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="90.546 363.407 20.75 23.641">
                                <path d="M108.294 363.407H93.548a3.003 3.003 0 0 0-3.002 3.002v17.637a3.003 3.003 0 0 0 3.002 3.002h14.746a3.003 3.003 0 0 0 3.002-3.002v-17.637a3.003 3.003 0 0 0-3.002-3.002ZM96.75 379.805l-2.208 2.42a.473.473 0 0 1-.344.153c-.016 0-.031 0-.047-.005a.459.459 0 0 1-.35-.228l-.91-1.578a.467.467 0 0 1 .169-.635.467.467 0 0 1 .635.17l.593 1.032 1.785-1.954a.467.467 0 0 1 .656-.032.467.467 0 0 1 .021.657Zm0-5.814-2.208 2.42a.473.473 0 0 1-.344.153c-.016 0-.031 0-.047-.005a.459.459 0 0 1-.35-.228l-.91-1.577a.467.467 0 0 1 .169-.636.467.467 0 0 1 .635.17l.588 1.032 1.784-1.954a.462.462 0 1 1 .683.625Zm0-5.808-2.208 2.42a.473.473 0 0 1-.344.153c-.016 0-.031 0-.047-.005a.459.459 0 0 1-.35-.228l-.91-1.578a.467.467 0 0 1 .169-.635.467.467 0 0 1 .635.17l.593 1.032 1.785-1.954a.467.467 0 0 1 .656-.032.467.467 0 0 1 .021.657Zm11.003 13.178c0 .615-.498 1.107-1.107 1.107h-7.132a1.106 1.106 0 1 1 0-2.213h7.132a1.103 1.103 0 0 1 1.107 1.106Zm0-6.104c0 .614-.498 1.106-1.107 1.106h-7.132a1.106 1.106 0 1 1 0-2.213h7.132c.61 0 1.107.492 1.107 1.107Zm0-6.105c0 .614-.498 1.106-1.107 1.106h-7.132a1.106 1.106 0 1 1 0-2.213h7.132c.61 0 1.107.493 1.107 1.107Z" fill-rule="evenodd" data-name="test_ctrl"/>
                            </svg>&nbsp;トレーニング管理
                        </a>
                        <ul class="dropdown-content position-absolute">
                            <li class="{{  \Request::segment(2) == 'question' && \Request::segment(3) == 'question-image' ? 'active' : ''  }}" style="padding: 15px 25px 0 25px;"><a href="{{ url('/admin/question/question-image') }}">画像・イラスト登録</a></li>
                            <li class="{{  \Request::segment(2) == 'question' && \Request::segment(3) == 'question-text' ? 'active' : ''  }}" style="padding: 15px 25px 0 25px;"><a href="{{ url('/admin/question/question-text') }}">テキスト登録</a></li>
                            <li class="{{  \Request::segment(2) == 'question' && \Request::segment(3) == 'question-2D' ? 'active' : ''  }}" style="padding: 15px 25px 0 25px;"><a href="{{ url('/admin/question/question-2D') }}">２D動画登録</a></li>
                            <li class="{{  \Request::segment(2) == 'question' && \Request::segment(3) == 'question-360' ? 'active' : ''  }}" style="padding: 15px 25px;"><a href="{{ url('/admin/question/question-360') }}">360°動画登録</a></li>
                        </ul>
                        
                    </li>
                    <li class="{{ \Request::segment(2) == 'video' ? 'active' : '' }}">
                        <a href="{{ url('admin/video') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="7.659 -0.003 22.402 23.093">
                                <path d="M9.233 17.706c.108-.153.253-.272.417-.354.161-.085.344-.13.53-.13h.032l2.07.455 2.658.58 4.615 1.01a3.212 3.212 0 0 0 2.8-.726l.157-.138 5.479-4.852 1.824-1.616a.664.664 0 0 0 .057-.937.66.66 0 0 0-.934-.056l-1.95 1.726-5.511 4.876a1.88 1.88 0 0 1-1.642.43l-3.149-.698-3.804-.84-2.666-.59h-.004v-.01l-.019-.003-.382-.084a1.185 1.185 0 0 1-.3-.152 1.152 1.152 0 0 1-.353-.417 1.156 1.156 0 0 1 .099-1.224c.107-.152.251-.272.416-.354.16-.085.343-.13.53-.13h.03l2.072.455 2.657.58 4.615 1.01c.224.051.45.073.678.073a3.203 3.203 0 0 0 2.122-.798l.157-.139 5.48-4.851 1.824-1.616a.659.659 0 0 0 .141-.811.66.66 0 0 0-1.02-.183l-1.95 1.726-5.51 4.876a1.876 1.876 0 0 1-1.642.43l-3.15-.698-3.803-.84-2.666-.59h-.004v-.01l-.018-.002-.382-.085c-.02-.007-.035-.016-.054-.023-.046-.02-.093-.036-.136-.063a.594.594 0 0 1-.11-.066 1.163 1.163 0 0 1-.354-.416 1.166 1.166 0 0 1 .515-1.578c.16-.086.343-.13.53-.13.032 0 .063.004.095.004l1.458.315 1.29.277 6.376 1.38a2.526 2.526 0 0 0 2.219-.578l3.92-3.452 2.345-2.065 1.657-1.461c.36-.319.511-.811.388-1.275a1.267 1.267 0 0 0-.963-.925l-.498-.107-3.636-.786-4.605-.994a2.527 2.527 0 0 0-2.218.578L11.9 6.037l-1.85 1.625-1.609 1.414a1.627 1.627 0 0 0-.173.233 2.504 2.504 0 0 0-.565 1.588 2.517 2.517 0 0 0 .81 1.847l-.006.006-.089.075a1.742 1.742 0 0 0-.173.234 2.518 2.518 0 0 0-.565 1.587c0 .54.174 1.055.48 1.478.097.134.21.255.333.368l-.008.008-.09.075a1.736 1.736 0 0 0-.172.234 2.473 2.473 0 0 0-.448.833 2.5 2.5 0 0 0 .362 2.232 2.486 2.486 0 0 0 1.288.928l.029.009 2.806.616 2.657.58 4.614 1.01a3.215 3.215 0 0 0 2.8-.726l.157-.138 5.48-4.852 1.824-1.616a.664.664 0 0 0 .057-.937.66.66 0 0 0-.934-.056l-1.95 1.725-5.512 4.877a1.88 1.88 0 0 1-1.641.429l-3.15-.697-3.803-.84-2.667-.59-.382-.086a1.162 1.162 0 0 1-.675-.58 1.156 1.156 0 0 1-.076-.884c.042-.126.1-.24.173-.34Z" fill-rule="evenodd" data-name="パス 16"/>
                            </svg>&nbsp;動画ライブラリー管理
                        </a>
                    </li>
                    <li class="{{ \Request::segment(2) == 'notification' ? 'active' : '' }}">
                        <a href="{{ url('admin/notification') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="88.094 494 24.854 28.352">
                                <g data-name="push">
                                    <g data-name="グループ 2">
                                    <path d="M97.123 519.666a3.495 3.495 0 0 0 6.8 0h-6.8Z" fill-rule="evenodd" data-name="パス 18"/>
                                    <path d="M109.634 511.639c-.038-.055-.076-.104-.114-.153H91.527c-.039.05-.077.104-.115.153-2.272 3.068-3.318 3.03-3.318 4.844v1.733h24.853v-1.733c0-1.82-1.04-1.782-3.313-4.844Z" fill-rule="evenodd" data-name="パス 19"/>
                                    <path d="M106.997 495.553a5.434 5.434 0 0 0 0 10.866 5.434 5.434 0 0 0 5.433-5.433 5.43 5.43 0 0 0-5.433-5.433Zm.986 8.604a.353.353 0 0 1-.354.354h-1.09a.353.353 0 0 1-.354-.354v-4.495h-.801a.353.353 0 0 1-.354-.355v-.61c0-.18.136-.332.316-.354.517-.06.942-.316 1.122-.676.06-.12.18-.196.316-.196h.845c.196 0 .354.158.354.354v6.332Z" fill-rule="evenodd" data-name="パス 20"/>
                                    </g>
                                <path d="M106.752 507.939a6.914 6.914 0 0 1-3.907-12.62c-.393-.78-1.287-1.319-2.322-1.319-1.4 0-2.54.992-2.54 2.212 0 .098.012.197.023.295-2.006 1.035-4.474 3.002-4.474 6.43 0 3.683-.185 5.345-.894 6.713h15.765a6.492 6.492 0 0 1-.6-1.787 7.409 7.409 0 0 1-1.051.076Z" fill-rule="evenodd" data-name="パス 21"/>
                                </g>
                            </svg>&nbsp;プッシュ通知
                        </a>
                    </li>
                    <li class="{{ \Request::segment(2) == 'learning' ? 'active' : '' }}">
                        <a href="{{ route('admin.learning.show') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="90.546 363.407 20.75 23.641">
                                <path d="M108.294 363.407H93.548a3.003 3.003 0 0 0-3.002 3.002v17.637a3.003 3.003 0 0 0 3.002 3.002h14.746a3.003 3.003 0 0 0 3.002-3.002v-17.637a3.003 3.003 0 0 0-3.002-3.002ZM96.75 379.805l-2.208 2.42a.473.473 0 0 1-.344.153c-.016 0-.031 0-.047-.005a.459.459 0 0 1-.35-.228l-.91-1.578a.467.467 0 0 1 .169-.635.467.467 0 0 1 .635.17l.593 1.032 1.785-1.954a.467.467 0 0 1 .656-.032.467.467 0 0 1 .021.657Zm0-5.814-2.208 2.42a.473.473 0 0 1-.344.153c-.016 0-.031 0-.047-.005a.459.459 0 0 1-.35-.228l-.91-1.577a.467.467 0 0 1 .169-.636.467.467 0 0 1 .635.17l.588 1.032 1.784-1.954a.462.462 0 1 1 .683.625Zm0-5.808-2.208 2.42a.473.473 0 0 1-.344.153c-.016 0-.031 0-.047-.005a.459.459 0 0 1-.35-.228l-.91-1.578a.467.467 0 0 1 .169-.635.467.467 0 0 1 .635.17l.593 1.032 1.785-1.954a.467.467 0 0 1 .656-.032.467.467 0 0 1 .021.657Zm11.003 13.178c0 .615-.498 1.107-1.107 1.107h-7.132a1.106 1.106 0 1 1 0-2.213h7.132a1.103 1.103 0 0 1 1.107 1.106Zm0-6.104c0 .614-.498 1.106-1.107 1.106h-7.132a1.106 1.106 0 1 1 0-2.213h7.132c.61 0 1.107.492 1.107 1.107Zm0-6.105c0 .614-.498 1.106-1.107 1.106h-7.132a1.106 1.106 0 1 1 0-2.213h7.132c.61 0 1.107.493 1.107 1.107Z" fill-rule="evenodd" data-name="test_ctrl"/>
                            </svg>&nbsp;学習分析</a>
                    </li>
                    <li class="{{ \Request::segment(2) == 'change-password' ? 'active' : '' }}">
                        <a href="{{ url('/admin/change-password') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="90.546 633 19.836 28.5">
                                <path d="M107.263 644.363v-4.65a6.718 6.718 0 0 0-6.714-6.713h-.17a6.718 6.718 0 0 0-6.714 6.714v4.649a9.917 9.917 0 0 0 6.799 17.137 9.917 9.917 0 0 0 6.799-17.137Zm-5.76 7.954v2.25a.686.686 0 0 1-.684.684h-.72a.686.686 0 0 1-.685-.685v-2.25a2.321 2.321 0 0 1 1.04-4.394c1.28 0 2.32 1.04 2.32 2.32.01.905-.51 1.69-1.27 2.075Zm2.715-9.914a9.873 9.873 0 0 0-3.754-.735c-1.33 0-2.6.26-3.755.735v-2.684a3.672 3.672 0 0 1 3.67-3.67h.17a3.672 3.672 0 0 1 3.67 3.67v2.684Z" fill-rule="evenodd" data-name="pass"/>
                            </svg>&nbsp;パスワード変更</a>
                    </li>
                    @if(in_array(Auth::user()->role_id, [\App\Consts::ADMIN]) )
                    <li class="{{ \Request::segment(2) == 'illustration' ? 'active' : '' }}">
                        <a href="{{ url('admin/illustration') }}">
                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="90 296 21.296 21.287">
                                <g data-name="人物のアイコン素材 その3"><path d="M100.647 307.747a5.874 5.874 0 1 0 .001-11.747 5.874 5.874 0 0 0 0 11.747Z" fill-rule="evenodd" data-name="パス 14"/>
                                    <path d="M110.953 315.47c-1.245-3.826-5.813-5.91-10.305-5.91-4.493 0-9.06 2.084-10.305 5.91a7.179 7.179 0 0 0-.343 1.817h21.296a7.202 7.202 0 0 0-.343-1.817Z" fill-rule="evenodd" data-name="パス 15"/>
                                </g>
                            </svg>&nbsp;アバター登録
                        </a>
                    </li>
                    @endif
                </ul>
            </li>
        </ul>
    </div>
</div>
