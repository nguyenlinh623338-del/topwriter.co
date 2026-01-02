namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CryptoPaymentController extends Controller
{
    public function handleCallback(Request $request)
    {
        Log::info('Coinbase Payment Callback:', $request->all());

        return response()->json(['message' => 'Payment received']);
    }
}