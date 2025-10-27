#!/usr/bin/env bash

BASE_URL="http://localhost:8000/api/v1"
OUTFILE="tests/api_results.txt"

mkdir -p tests
echo "API test run - $(date)" > "$OUTFILE"

# Counters
failures=0
total=0

request_and_log() {
  local method="$1"
  local url="$2"
  local data="$3"
  local label="$4"

  total=$((total+1))
  echo "\n=== $label ===" | tee -a "$OUTFILE"

  # Build curl command
  if [ -n "$data" ]; then
    resp=$(curl -s -w "\nHTTP_STATUS:%{http_code}" -H "Content-Type: application/json" -X "$method" -d "$data" "$url")
  else
    resp=$(curl -s -w "\nHTTP_STATUS:%{http_code}" -X "$method" "$url")
  fi

  # Extract status code (last line)
  http_code=$(echo "$resp" | tr -d '\r' | sed -n 's/.*HTTP_STATUS:\([0-9]\{3\}\)$/\1/p')
  body=$(echo "$resp" | sed -e 's/HTTP_STATUS:[0-9]\{3\}$//')

  echo "URL: $url" >> "$OUTFILE"
  echo "HTTP_STATUS: $http_code" >> "$OUTFILE"
  echo "RESPONSE_BODY:" >> "$OUTFILE"
  echo "$body" >> "$OUTFILE"

  # mark failure if status not 2xx
  if [[ ! "$http_code" =~ ^2[0-9]{2}$ ]]; then
    failures=$((failures+1))
    echo "Result: FAILURE" >> "$OUTFILE"
  else
    echo "Result: OK" >> "$OUTFILE"
  fi
}

# Run tests
request_and_log GET "http://localhost:8000/" "" "GET / (root)"

# Users
request_and_log GET "$BASE_URL/users" "" "GET /users"
request_and_log GET "$BASE_URL/users/1" "" "GET /users/1"
request_and_log POST "$BASE_URL/users" '{"name":"Test User","email":"testuser+ci@example.com","password":"password"}' "POST /users"

# Comptes
request_and_log GET "$BASE_URL/comptes" "" "GET /comptes"

# create unique account number
acct_num="ACC_TEST_$(date +%s)"
create_compte_payload=$(jq -n --arg num "$acct_num" --argjson tit 1 --arg type "courant" --arg devise "XOF" --arg statut "actif" '{numero_compte: $num, titulaire_id: $tit, type: $type, devise: $devise, statut: $statut}')
request_and_log POST "$BASE_URL/comptes" "$create_compte_payload" "POST /comptes"

# try to extract created compte id from last response body
last_compte_body=$(tail -n +1 "$OUTFILE" | tr -d '\r' | tac | sed -n '/POST \/comptes/,$p' | tac)
created_compte_id=$(echo "$last_compte_body" | tr -d '\n' | sed -n 's/.*"id"\s*:\s*"\([^"}]*\)".*/\1/p')
if [ -z "$created_compte_id" ]; then
  created_compte_id="$acct_num"
fi
echo "Created compte id (attempt): $created_compte_id" >> "$OUTFILE"

request_and_log GET "$BASE_URL/comptes/$created_compte_id" "" "GET /comptes/{id}"
request_and_log PUT "$BASE_URL/comptes/$created_compte_id" '{"type":"epargne","devise":"EUR"}' "PUT /comptes/{id}"
request_and_log POST "$BASE_URL/comptes/$created_compte_id/bloquer" '{"motif":"Test blocage"}' "POST /comptes/{id}/bloquer"
request_and_log POST "$BASE_URL/comptes/$created_compte_id/debloquer" "" "POST /comptes/{id}/debloquer"

# Transactions
request_and_log GET "$BASE_URL/transactions" "" "GET /transactions"
create_tx_payload=$(jq -n --arg acc "$created_compte_id" --argjson montant 1000 --arg type "depot" --arg desc "Test crédit" '{compte_id: $acc, montant: $montant, type: $type, description: $desc}')
request_and_log POST "$BASE_URL/transactions" "$create_tx_payload" "POST /transactions"

# extract tx id
last_tx_body=$(tail -n +1 "$OUTFILE" | tr -d '\r' | tac | sed -n '/POST \/transactions/,$p' | tac)
created_tx_id=$(echo "$last_tx_body" | tr -d '\n' | sed -n 's/.*"id"\s*:\s*"\([^"}]*\)".*/\1/p')
if [ -z "$created_tx_id" ]; then
  created_tx_id="1"
fi
echo "Created transaction id (attempt): $created_tx_id" >> "$OUTFILE"

request_and_log GET "$BASE_URL/transactions/$created_tx_id" "" "GET /transactions/{id}"
request_and_log PUT "$BASE_URL/transactions/$created_tx_id" '{"description":"Updated description"}' "PUT /transactions/{id}"
request_and_log DELETE "$BASE_URL/transactions/$created_tx_id" "" "DELETE /transactions/{id}"

# Final summary
echo "\n=== Summary ===" >> "$OUTFILE"
echo "Total requests: $total" >> "$OUTFILE"
echo "Failures: $failures" >> "$OUTFILE"

if [ "$failures" -gt 0 ]; then
  echo "One or more requests failed. See $OUTFILE for details." >&2
  exit 2
else
  echo "All tests passed." >> "$OUTFILE"
  echo "All tests passed. Output saved to $OUTFILE"
  exit 0
fi
