<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\User;
use App\Models\Order;
use Illuminate\View\View;
use App\Models\UserBilling;
use App\Models\UserPayment;
use App\Models\UserShipping;
use Illuminate\Http\Request;
use App\Services\UserService;
use App\Services\OrderService;
use App\Mail\ResetPasswordMail;
use App\Services\CartItemService;
use App\Services\UserPaymentService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Services\UserShippingService;

class HomeController extends Controller
{
	// private UserService $userService;
	// private UserPaymentService $userPaymentService;
	// private UserShippingService $userShippingService;
	// private OrderService $orderService;
	// private CartItemService $cartItemService;

	public function __construct(
		// UserService $userService,
		// UserPaymentService $userPaymentService,
		// UserShippingService $userShippingService,
		// OrderService $orderService,
		// CartItemService $cartItemService
	) {
		// $this->userService = $userService;
		// $this->userPaymentService = $userPaymentService;
		// $this->userShippingService = $userShippingService;
		// $this->orderService = $orderService;
		// $this->cartItemService = $cartItemService;
	}

	public function index(): View
	{
		return view('index');
	}

	public function login(): View
	{
		// TODO

		// Passing data to the view
		// return view('profile', ['classActiveLogin' => true]);
	}

	public function about(): View
	{
		return view('about');
	}

	public function faq(): View
	{
		return view('faq');
	}

	public function catalog(): View
	{
		$user = Auth::user(); // Get the currently authenticated user
		$bookList = Book::all(); // Fetch all books

		return view('catalog', [
			'user' => $user,               // Pass the user data to the view
			'bookList' => $bookList,       // Pass the list of books to the view
			'activeAll' => true            // Add additional data for the view
		]);
	}

	public function book($id): View
	{
		$user = Auth::user(); // Get the currently authenticated user
		$book = Book::findOrFail($id); // Find the book by ID or fail with 404

		$qtyList = range(1, 10); // Generate a list of quantities

		return view('book', [
			'user' => $user,         // Pass the user data to the view
			'book' => $book,         // Pass the book details to the view
			'qtyList' => $qtyList,   // Pass the quantity list to the view
			'qty' => 1               // Default quantity
		]);
	}

}
