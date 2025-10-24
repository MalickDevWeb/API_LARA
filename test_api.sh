#!/bin/bash
# Script de test API complet
BASE_URL="http://localhost:8080/api/v1"
TOKEN="YOUR_BEARER_TOKEN"

echo "=== TEST COMPLET DE L'API BANCAIRE ==="

# 1. Test de connexion
echo "1. Test de connexion..."
curl -s $BASE_URL/ -H "Authorization: Bearer $TOKEN" | jq .message

# 2. Lister les utilisateurs
echo "2. Récupération des utilisateurs..."
curl -s $BASE_URL/users -H "Authorization: Bearer $TOKEN" | jq ".data | length"

# 3. Lister les comptes
echo "3. Récupération des comptes..."
curl -s $BASE_URL/comptes -H "Authorization: Bearer $TOKEN" | jq ".data[0] | {numero_compte, type, statut}"

# 4. Lister les transactions
echo "4. Récupération des transactions..."
curl -s $BASE_URL/transactions -H "Authorization: Bearer $TOKEN" | jq ".data[0] | {type, montant, statut}"

echo "=== TESTS TERMINÉS ==="