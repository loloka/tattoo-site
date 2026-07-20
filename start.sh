#!/bin/bash
echo "Запускаю сайт в Docker..."
docker compose up -d --build
echo ""
echo "================================"
echo "  Сайт:    http://localhost:8080"
echo "  Админка: http://localhost:8080/admin/login.php"
echo "================================"
echo ""
open http://localhost:8080 2>/dev/null || xdg-open http://localhost:8080 2>/dev/null || true
