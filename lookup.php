<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>GEMI Number Lookup</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
    <div class="container mx-auto px-4 py-8">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold text-center mb-8 text-blue-800">Company Details Lookup</h1>
            
            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                <form method="post" class="space-y-4">
                    <div>
                        <label for="gemiNumber" class="block text-sm font-medium text-gray-700 mb-1">GEMI Number:</label>
                        <input type="text" id="gemiNumber" name="gemiNumber" required
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                               placeholder="Enter GEMI number">
                    </div>
                    <button type="submit" name="submit"
                            class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-200">
                        Fetch Company Details
                    </button>
                </form>
            </div>

            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
                $gemiNumber = $_POST['gemiNumber'];
                
                // Validate input
                if (!preg_match('/^\d+$/', $gemiNumber)) {
                    echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8" role="alert">
                            <p class="font-bold">Error</p>
                            <p>Please enter a valid GEMI number (numeric only).</p>
                          </div>';
                } else {
                    // Prepare the API request
                    $url = 'https://publicity.businessportal.gr/api/company/details';
                    $payload = json_encode([
                        'query' => ['arGEMI' => $gemiNumber],
                        'token' => null,
                        'language' => 'el'
                    ]);
                    
                    // Initialize cURL session
                    $ch = curl_init($url);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                    curl_setopt($ch, CURLOPT_POST, true);
                    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                    curl_setopt($ch, CURLOPT_HTTPHEADER, [
                        'Content-Type: application/json',
                        'Content-Length: ' . strlen($payload)
                    ]);
                    
                    // Execute the request
                    $response = curl_exec($ch);
                    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                    curl_close($ch);
                    
                    // Process the response
                    if ($httpCode === 200) {
                        $data = json_decode($response, true);
                        
                        if (isset($data['companyInfo']['payload']['company'])) {
                            $company = $data['companyInfo']['payload']['company'];
                            $management = $data['companyInfo']['payload']['managementPersons'] ?? [];
                            $moreInfo = $data['companyInfo']['payload']['moreInfo'] ?? [];
                            $kadData = $data['companyInfo']['payload']['kadData'] ?? [];
                            ?>
                            
                            <div class="bg-white rounded-lg shadow-md p-6 mb-8">
                                <h2 class="text-2xl font-bold mb-4 text-blue-800">Company Information</h2>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">GEMI Number</p>
                                        <p class="font-semibold"><?= htmlspecialchars($company['id'] ?? 'N/A') ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Name</p>
                                        <p class="font-semibold"><?= htmlspecialchars($company['name'] ?? 'N/A') ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">AFM</p>
                                        <p class="font-semibold"><?= htmlspecialchars($company['afm'] ?? 'N/A') ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Status</p>
                                        <p class="font-semibold"><?= htmlspecialchars($company['companyStatus']['status'] ?? 'N/A') ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Legal Type</p>
                                        <p class="font-semibold"><?= htmlspecialchars($company['legalType']['desc'] ?? 'N/A') ?></p>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-500">Registration Date</p>
                                        <p class="font-semibold"><?= htmlspecialchars($company['dateStart'] ?? 'N/A') ?></p>
                                    </div>
                                </div>
                                
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold mb-2 text-blue-700">Address</h3>
                                    <p><?= htmlspecialchars(($company['company_street'] ?? '') . ' ' . ($company['company_street_number'] ?? '')) ?></p>
                                    <p><?= htmlspecialchars($company['company_zip_code'] ?? '') ?> <?= htmlspecialchars($company['company_city'] ?? '') ?></p>
                                    <p><?= htmlspecialchars($company['company_region'] ?? '') ?>, <?= htmlspecialchars($company['company_municipality'] ?? '') ?></p>
                                </div>
                                
                                <?php if (!empty($moreInfo)): ?>
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold mb-2 text-blue-700">Contact Information</h3>
                                    <p><span class="font-medium">Phone:</span> <?= htmlspecialchars($moreInfo['telephone'] ?? 'N/A') ?></p>
                                    <p><span class="font-medium">Email:</span> <?= htmlspecialchars($moreInfo['email'] ?? 'N/A') ?></p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($management)): ?>
                                <div class="mb-6">
                                    <h3 class="text-lg font-semibold mb-2 text-blue-700">Management</h3>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">AFM</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Since</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php foreach ($management as $person): ?>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($person['firstName'] ?? '') ?> <?= htmlspecialchars($person['lastName'] ?? '') ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($person['afm'] ?? 'N/A') ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($person['capacityDescr'] ?? 'N/A') ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($person['dateFrom'] ?? 'N/A') ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php endif; ?>
                                
                                <?php if (!empty($kadData)): ?>
                                <div>
                                    <h3 class="text-lg font-semibold mb-2 text-blue-700">KAD Activities</h3>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Activity Type</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">KAD Code</th>
                                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php foreach ($kadData as $activity): ?>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($activity['activities'] ?? 'N/A') ?></td>
                                                    <td class="px-6 py-4 whitespace-nowrap"><?= htmlspecialchars($activity['kad'] ?? 'N/A') ?></td>
                                                    <td class="px-6 py-4"><?= htmlspecialchars($activity['descr'] ?? 'N/A') ?></td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                            
                            <?php
                        } else {
                            echo '<div class="bg-yellow-100 border-l-4 border-yellow-500 text-yellow-700 p-4 mb-8" role="alert">
                                    <p class="font-bold">No Data Found</p>
                                    <p>No company information found for the provided GEMI number.</p>
                                  </div>';
                        }
                    } else {
                        echo '<div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-8" role="alert">
                                <p class="font-bold">API Error</p>
                                <p>Failed to fetch company details. HTTP Status: ' . $httpCode . '</p>
                                <p>Response: ' . htmlspecialchars($response) . '</p>
                              </div>';
                    }
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
