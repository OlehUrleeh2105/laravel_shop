@php
use App\Models\ProductLike;
use Illuminate\Support\Facades\DB;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <style>
        .sidebar {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            padding: 58px 0 0;
            box-shadow: 0 2px 5px 0 rgb(0 0 0 / 5%), 0 2px 10px 0 rgb(0 0 0 / 5%);
            width: 240px;
            z-index: 600;
        }

        .sidebar-sticky {
            position: relative;
            top: 0;
            height: calc(100vh - 48px);
            padding-top: 0.5rem;
            overflow-x: hidden;
            overflow-y: auto;
        }
    </style>

</head>

<body>

    <header>
        <div class="p-3 text-center bg-primary border-bottom">
            <div class="container">
                <div class="row justify-content-between">
                    <div class="col-md-4 d-flex justify-content-center justify-content-md-start mb-3 mb-md-0">
                        <a href="{{ url('/') }}" class="h5 d-flex align-items-center text-decoration-none text-dark mb-0">
                            <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" viewBox="-20 0 190 190" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M105.81 85.9105C101.04 90.6805 101.26 99.6205 101.26 99.6205H98.73C98.73 99.6205 98.93 90.7405 94.56 86.3305C90.19 81.9205 82.39 81.2705 82.39 81.2705L82.64 78.5705C82.64 78.5705 91.64 77.5005 94.64 74.4805C98.76 70.3105 98.52 62.7205 98.52 62.7205L101.09 62.5005C101.09 62.5005 101.55 71.0805 105.09 74.6405C108.63 78.2005 117.79 78.5405 117.79 78.5405L117.91 81.3905C117.91 81.3905 110.57 81.1505 105.81 85.9105ZM83 119.21H84.61C84.61 119.21 85.14 112.35 87.44 110.39C88.9508 109.238 90.7058 108.448 92.57 108.08C95.27 109.48 96.57 111.95 96.57 115.32C96.57 122.19 86.14 126.54 77.06 126.54C66.12 126.54 52.06 121.41 52.06 112.54C52.06 101.38 72.73 101.01 70.93 87.5405C77.84 88.7105 81.79 91.6905 82.93 96.2905C82.93 97.5605 82.7 101.19 80.57 103.29C78.67 105.2 72.83 105.83 72.83 105.83L72.67 107.54C72.67 107.54 77.52 107.9 80.28 110.69C83.04 113.48 83 119.21 83 119.21ZM106.26 103.41C106.259 101.105 106.092 98.8021 105.76 96.5205L111.63 92.4405C112.375 95.9141 112.744 99.4579 112.73 103.01V117.6L106.26 112.36V103.41ZM88.87 71.9405C87.2211 71.1769 85.5077 70.5612 83.75 70.1005L77.75 69.2305L78.56 63.0905C79.46 57.7705 71.91 57.6405 72.65 63.2305L73.32 69.2305L67.16 70.5505C52.48 74.2605 44.96 86.5505 44.96 103.44V117.16C44.96 117.16 50.33 132.7 77.28 132.7C90.21 132.7 100.28 127.7 102.28 118.13C111.43 120.21 116.35 128.5 109.47 137.36C96.47 154.15 53.78 151.49 41.93 137.44C39.71 134.8 38.41 130.52 38.41 126.44C38.41 126.44 38.47 110.05 38.47 103.01C38.47 81.0105 50.53 68.1905 65.59 64.3905C64.69 58.8305 68.14 52.4805 75.59 52.4805C83.04 52.4805 86.24 58.9105 85.29 64.4805C87.6302 65.0983 89.9069 65.9353 92.09 66.9805L88.87 71.9405Z" fill="#000000" />
                            </svg>
                            <span>shtShop</span>
                        </a>
                    </div>

                    <!-- <div class="col-md-4">
                        @auth
                        <input autocomplete="off" id="search-title" type="search" name="title" class="form-control rounded" placeholder="Search" />
                        @endauth
                    </div> -->

                    <div class="col-md-4 d-flex justify-content-center justify-content-md-end align-items-center">
                        <div class="d-flex align-items-center">
                            @guest
                            @if (Route::has('login'))
                            <a href="{{  route('login') }}" class="text-decoration-none text-dark">
                                <span class="" style="margin-right: 5px;">Login</span>
                                <svg class="text-success" xmlns="http://www.w3.org/2000/svg" width="27" height="27" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z" />
                                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                                </svg>
                            </a>
                            @endif
                            @else
                            <a class="text-reset me-5 position-relative" href="{{ route('orders') }}">
                                <svg width="24" height="24" viewBox="0 0 1024 1024" fill="#000000" class="icon" version="1.1" xmlns="http://www.w3.org/2000/svg">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path d="M300 462.4h424.8v48H300v-48zM300 673.6H560v48H300v-48z" fill=""></path>
                                        <path d="M818.4 981.6H205.6c-12.8 0-24.8-2.4-36.8-7.2-11.2-4.8-21.6-11.2-29.6-20-8.8-8.8-15.2-18.4-20-29.6-4.8-12-7.2-24-7.2-36.8V250.4c0-12.8 2.4-24.8 7.2-36.8 4.8-11.2 11.2-21.6 20-29.6 8.8-8.8 18.4-15.2 29.6-20 12-4.8 24-7.2 36.8-7.2h92.8v47.2H205.6c-25.6 0-47.2 20.8-47.2 47.2v637.6c0 25.6 20.8 47.2 47.2 47.2h612c25.6 0 47.2-20.8 47.2-47.2V250.4c0-25.6-20.8-47.2-47.2-47.2H725.6v-47.2h92.8c12.8 0 24.8 2.4 36.8 7.2 11.2 4.8 21.6 11.2 29.6 20 8.8 8.8 15.2 18.4 20 29.6 4.8 12 7.2 24 7.2 36.8v637.6c0 12.8-2.4 24.8-7.2 36.8-4.8 11.2-11.2 21.6-20 29.6-8.8 8.8-18.4 15.2-29.6 20-12 5.6-24 8-36.8 8z" fill=""></path>
                                        <path d="M747.2 297.6H276.8V144c0-32.8 26.4-59.2 59.2-59.2h60.8c21.6-43.2 66.4-71.2 116-71.2 49.6 0 94.4 28 116 71.2h60.8c32.8 0 59.2 26.4 59.2 59.2l-1.6 153.6z m-423.2-47.2h376.8V144c0-6.4-5.6-12-12-12H595.2l-5.6-16c-11.2-32.8-42.4-55.2-77.6-55.2-35.2 0-66.4 22.4-77.6 55.2l-5.6 16H335.2c-6.4 0-12 5.6-12 12v106.4z" fill=""></path>
                                    </g>
                                </svg>
                                <span class="badge rounded-pill badge-notification bg-danger position-absolute top-0">{{ $count = DB::table(DB::raw("(SELECT order_id FROM product_order WHERE user_id = " . Auth::user()->id . " GROUP BY order_id) as count"))->count(); }}</span>
                            </a>

                            <a class="text-reset me-5 position-relative" href="{{ route('liked') }}">
                                <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" width="23" height="23">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12 6.00019C10.2006 3.90317 7.19377 3.2551 4.93923 5.17534C2.68468 7.09558 2.36727 10.3061 4.13778 12.5772C5.60984 14.4654 10.0648 18.4479 11.5249 19.7369C11.6882 19.8811 11.7699 19.9532 11.8652 19.9815C11.9483 20.0062 12.0393 20.0062 12.1225 19.9815C12.2178 19.9532 12.2994 19.8811 12.4628 19.7369C13.9229 18.4479 18.3778 14.4654 19.8499 12.5772C21.6204 10.3061 21.3417 7.07538 19.0484 5.17534C16.7551 3.2753 13.7994 3.90317 12 6.00019Z" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </g>
                                </svg>
                                <span class="badge rounded-pill badge-notification bg-danger position-absolute top-0">{{ ProductLike::where(['user_id' => Auth::user()->id, 'is_liked' => 1])->count() }}</span>
                            </a>

                            <a class="text-reset me-5 position-relative" href="{{ route('cart') }}">
                                <svg version="1.1" id="Uploaded to svgrepo.com" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 32 32" width="27" height="27" xml:space="preserve" fill="#000000">
                                    <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                    <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                    <g id="SVGRepo_iconCarrier">
                                        <style type="text/css">
                                            .linesandangles_een {
                                                fill: #000;
                                            }
                                        </style>
                                        <path class="linesandangles_een" d="M25.414,14l-6.707-6.707l-1.414,1.414L22.586,14H9.414l5.293-5.293l-1.414-1.414L6.586,14H4 l2,14h20l2-14H25.414z M24.265,26H7.735L6.306,16h19.388L24.265,26z M11,20H9v-2h2V20z M15,20h-2v-2h2V20z M19,20h-2v-2h2V20z M23,20h-2v-2h2V20z M11,24H9v-2h2V24z M15,24h-2v-2h2V24z M19,24h-2v-2h2V24z M23,24h-2v-2h2V24z"></path>
                                    </g>
                                </svg>

                                @php
                                $cart = session()->get('cart');
                                $cartCount = !$cart ? 0 : count($cart);
                                @endphp

                                <span class="badge rounded-pill badge-notification bg-danger position-absolute top-0">{{ $cartCount }}</span>
                            </a>

                            <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                    document.getElementById('logout-form').submit();">
                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" fill="currentColor" class="bi bi-box-arrow-right" viewBox="0 0 16 16">
                                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0z" />
                                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z" />
                                </svg>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                            @endguest

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

</body>

</html>
