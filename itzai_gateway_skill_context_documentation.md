# ItzAI Gateway — Skill / Context Documentation

## Overview

ItzAI Gateway adalah sistem backend AI Gateway multi-provider seperti OpenRouter yang mendukung:

- Multi AI Provider
- Dynamic API Key Management
- Auto API Key Rotation
- Auto Provider Failover
- Streaming Response
- Monitoring & Logging
- Queue System
- Redis Cache
- REST API Universal

Tujuan utama:

- Membuat satu endpoint universal AI
- Menghindari rate limit dengan auto rotation
- Memungkinkan penggunaan banyak provider AI gratis
- Menjadi AI Gateway scalable untuk aplikasi mobile/web

---

# Context Sistem

## Problem

Provider AI gratis seperti Gemini atau Groq memiliki:

- Rate limit
- Request limit
- Daily quota
- Timeout
- Overload

Jika hanya menggunakan satu API key:
- aplikasi mudah error
- chatbot berhenti bekerja
- user experience buruk

---

# Solusi

Gunakan:

- banyak provider AI
- banyak API key
- sistem auto rotation
- sistem fallback otomatis

Dengan konsep:

```txt
User Request
     ↓
AI Gateway
     ↓
Provider Manager
     ↓
Cari API Key Aktif
     ↓
Kirim Request
     ↓
Jika limit/error
     ↓
Switch API Key
     ↓
Jika provider down
     ↓
Fallback provider lain
```

---

# Supported Providers

## Gemini

Website:
https://aistudio.google.com

Kelebihan:
- gratis besar
- cepat
- multimodal
- context besar
- bagus untuk chatbot

Model:
- gemini-2.0-flash
- gemini-1.5-flash

---

## Groq

Website:
https://groq.com

Kelebihan:
- sangat cepat
- cocok streaming
- latency rendah

Model:
- llama
- deepseek
- mixtral

---

## OpenRouter

Website:
https://openrouter.ai

Kelebihan:
- universal gateway
- banyak model gratis
- OpenAI compatible

Model:
- DeepSeek
- Qwen
- Llama
- Gemini
- Claude

---

# Recommended Stack

## Backend

- Laravel 12
- PHP 8.3
- Redis
- MySQL

## Queue

- Laravel Horizon

## Realtime

- Laravel Reverb
- SSE

## Admin Panel

- FilamentPHP

## Frontend

- Expo React Native

---

# High Level Architecture

```txt
┌────────────────────┐
│ Mobile/Web Client  │
│ Expo / React App   │
└─────────┬──────────┘
          │
          ▼
┌────────────────────┐
│ Laravel API Gateway│
└─────────┬──────────┘
          │
          ▼
┌─────────────────────────┐
│      AI Core Engine     │
│-------------------------│
│ Provider Manager        │
│ Key Rotation            │
│ Failover Manager        │
│ Retry System            │
│ Streaming Service       │
│ Cache Layer             │
│ Logging System          │
└─────────┬───────────────┘
          │
 ┌────────┼────────┐
 ▼        ▼        ▼
Gemini   Groq   OpenRouter
```

---

# Folder Structure

```txt
app/
├── Services/
│   └── AI/
│       ├── AIManager.php
│       ├── ProviderManager.php
│       ├── FailoverManager.php
│       ├── KeyRotator.php
│       ├── RetryService.php
│       ├── StreamingService.php
│       └── Drivers/
│           ├── GeminiDriver.php
│           ├── GroqDriver.php
│           └── OpenRouterDriver.php
│
├── Models/
│   ├── AiProvider.php
│   ├── AiApiKey.php
│   ├── AiLog.php
│   ├── AiConversation.php
│   └── AiModel.php
│
├── Http/
│   └── Controllers/
│       └── Api/
│           └── ChatController.php
│
├── Repositories/
│   ├── ApiKeyRepository.php
│   └── ProviderRepository.php
│
├── Jobs/
│   ├── ProcessAiLog.php
│   └── CleanupCooldownKeys.php
```

---

# Database Schema

## ai_providers

```sql
CREATE TABLE ai_providers (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    slug VARCHAR(100),
    base_url TEXT,
    is_active BOOLEAN DEFAULT TRUE,
    priority INT DEFAULT 1,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

## ai_api_keys

```sql
CREATE TABLE ai_api_keys (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    provider_id BIGINT,
    api_key TEXT,
    status ENUM('active','inactive','limited') DEFAULT 'active',
    priority INT DEFAULT 1,
    usage_count BIGINT DEFAULT 0,
    error_count BIGINT DEFAULT 0,
    cooldown_until TIMESTAMP NULL,
    last_used_at TIMESTAMP NULL,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

## ai_logs

```sql
CREATE TABLE ai_logs (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    provider_id BIGINT,
    api_key_id BIGINT,
    model VARCHAR(255),
    prompt_tokens INT DEFAULT 0,
    completion_tokens INT DEFAULT 0,
    total_tokens INT DEFAULT 0,
    response_time FLOAT DEFAULT 0,
    status VARCHAR(50),
    error_message TEXT NULL,
    created_at TIMESTAMP NULL
);
```

---

## ai_conversations

```sql
CREATE TABLE ai_conversations (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    user_id BIGINT NULL,
    session_id VARCHAR(255),
    role VARCHAR(50),
    message LONGTEXT,
    provider_id BIGINT NULL,
    model VARCHAR(255),
    created_at TIMESTAMP NULL
);
```

---

## ai_models

```sql
CREATE TABLE ai_models (
    id BIGINT PRIMARY KEY AUTO_INCREMENT,
    provider_id BIGINT,
    model_name VARCHAR(255),
    is_active BOOLEAN DEFAULT TRUE,
    is_free BOOLEAN DEFAULT TRUE,
    context_length INT DEFAULT 0,
    created_at TIMESTAMP NULL,
    updated_at TIMESTAMP NULL
);
```

---

# Skill: API Key Rotation

## Tujuan

Menghindari rate limit API.

## Cara Kerja

1. Ambil API key aktif
2. Gunakan untuk request
3. Jika terkena limit:
   - set cooldown
4. Cari key lain
5. Jika semua key habis:
   - switch provider

---

# Skill: Cooldown System

## Konsep

API key yang limit tidak langsung dipakai lagi.

Contoh:

```php
$key->update([
    'cooldown_until' => now()->addHour()
]);
```

---

# Skill: Provider Failover

## Konsep

Jika provider utama gagal:
- otomatis pindah provider lain

Flow:

```txt
Gemini gagal
   ↓
Groq
   ↓
OpenRouter
```

---

# Skill: Retry Mechanism

Retry hanya untuk:

- timeout
- connection reset
- 503
- overload

Jangan retry untuk:

- invalid API key
- unauthorized
- forbidden

---

# Skill: Streaming Response

Gunakan:

- SSE
- chunk response

Tujuan:
- realtime typing seperti ChatGPT
- UX lebih baik

---

# Skill: Universal Response Format

Gunakan satu format untuk semua provider.

```json
{
  "provider": "gemini",
  "model": "gemini-2.0-flash",
  "message": "response ai"
}
```

---

# Driver Pattern

Semua provider menggunakan interface yang sama.

```php
interface AIProviderInterface
{
    public function chat(array $payload);
}
```

---

# Gemini Driver Example

```php
class GeminiDriver implements AIProviderInterface
{
    public function chat(array $payload)
    {
        // request Gemini API
    }
}
```

---

# Groq Driver Example

```php
class GroqDriver implements AIProviderInterface
{
    public function chat(array $payload)
    {
        // request Groq API
    }
}
```

---

# OpenRouter Driver Example

```php
class OpenRouterDriver implements AIProviderInterface
{
    public function chat(array $payload)
    {
        // request OpenRouter API
    }
}
```

---

# Redis Usage

Redis digunakan untuk:

- response cache
- queue
- session
- model cache
- rate limiter

---

# Queue Usage

Queue digunakan untuk:

- logging
- analytics
- cleanup
- monitoring

---

# Logging System

Data yang dicatat:

- provider
- API key
- response time
- total tokens
- request status
- error

---

# Admin Panel Features

Menggunakan FilamentPHP.

Fitur:

- CRUD provider
- CRUD API key
- disable key
- monitoring usage
- statistik request
- error monitoring
- cooldown monitor
- test API key

---

# Security

## API Key Encryption

```php
protected $casts = [
    'api_key' => 'encrypted'
];
```

---

# Best Practices

## Jangan lakukan

- expose API key di frontend
- hardcode API key
- spam request
- bypass illegal quota

---

# Recommended Flow

```txt
Mobile App
    ↓
Laravel Gateway
    ↓
Provider Manager
    ↓
Gemini / Groq / OpenRouter
```

---

# Future Upgrade

## Bisa ditambahkan

- user billing
- token quota
- OCR
- image generation
- speech to text
- embeddings
- vector database
- RAG AI
- AI agents
- workflow automation

---

# Final Goal

Membangun AI Gateway scalable seperti:

- OpenRouter
- Portkey
- AI Gateway

Dengan:
- multi provider
- auto key rotation
- auto failover
- realtime streaming
- monitoring
- scalable architecture

