$kabags = \App\Models\User::where('role', 'kepala_bagian')->get(['id', 'name', 'email']);
echo "Kabag users: " . json_encode($kabags) . "\n";

$disposisis = \App\Models\Disposisi::all(['id', 'ke_user_id', 'status']);
echo "Disposisis: " . json_encode($disposisis) . "\n";
