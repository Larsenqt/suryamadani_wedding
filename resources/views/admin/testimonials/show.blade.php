@extends('layouts.admin-sidebar')

@section('title', 'Kelola Testimoni Dokumentasi')

@section('content')
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: #f0f2f5;
            color: #1a1a2e;
        }

        .admin-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 2rem;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            color: #475569;
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s;
            border: 1px solid #e2e8f0;
            margin-bottom: 1.5rem;
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateX(-2px);
        }

        .card {
            background: white;
            border-radius: 1.5rem;
            box-shadow: 0 20px 35px -10px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            border: 1px solid #e9eef3;
        }

        .card-header {
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #f0f2f5;
            background: linear-gradient(135deg, #f8fafc 0%, #ffffff 100%);
        }

        .card-header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            background: linear-gradient(135deg, #1e3c72 0%, #2a5298 100%);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .card-body {
            padding: 2rem;
        }

        .testimonial-detail {
            text-align: center;
        }

        .testimonial-image {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            object-fit: cover;
            margin: 0 auto 1.5rem;
            border: 4px solid #e0e7ff;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .testimonial-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
            margin-bottom: 1rem;
        }

        .testimonial-quote {
            font-size: 1rem;
            line-height: 1.8;
            color: #475569;
            background: #f8fafc;
            padding: 1.5rem;
            border-radius: 1rem;
            margin: 1.5rem 0;
            position: relative;
            font-style: italic;
        }

        .testimonial-quote::before {
            content: '"';
            font-size: 4rem;
            color: #3b82f6;
            position: absolute;
            top: -10px;
            left: 10px;
            opacity: 0.3;
            font-family: serif;
        }

        .testimonial-quote::after {
            content: '"';
            font-size: 4rem;
            color: #3b82f6;
            position: absolute;
            bottom: -30px;
            right: 10px;
            opacity: 0.3;
            font-family: serif;
        }

        .action-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            margin-top: 2rem;
        }

        .btn-primary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-weight: 500;
            font-size: 0.875rem;
            transition: all 0.3s ease;
            border: none;
            cursor: pointer;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.3);
        }

        .btn-secondary {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            color: #2563eb;
            padding: 0.75rem 1.5rem;
            border-radius: 0.75rem;
            text-decoration: none;
            font-size: 0.875rem;
            font-weight: 500;
            border: 2px solid #e2e8f0;
            transition: all 0.2s;
        }

        .btn-secondary:hover {
            border-color: #2563eb;
            background: #eff6ff;
        }

        @media (max-width: 640px) {
            .admin-container {
                padding: 1rem;
            }
            
            .testimonial-title {
                font-size: 1.25rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-container">
        <a href="{{ route('admin.testimonials.index') }}" class="btn-back">
            ← Kembali ke Daftar Testimoni
        </a>

        <div class="card">
            <div class="card-header">
                <h2>
                    <span>👤</span> Detail Testimoni
                </h2>
            </div>
            <div class="card-body">
                <div class="testimonial-detail">
                    @if($testimonial->image && file_exists(storage_path('app/public/'.$testimonial->image)))
                        <img src="{{ asset('storage/'.$testimonial->image) }}" class="testimonial-image" alt="{{ $testimonial->title }}">
                    @else
                        <div class="testimonial-image" style="display: flex; align-items: center; justify-content: center; background: #f1f5f9;">
                            No Image
                        </div>
                    @endif
                    
                    <div class="testimonial-title">
                        {{ $testimonial->title }}
                    </div>
                    
                    <div class="testimonial-quote">
                        {{ $testimonial->description }}
                    </div>

                    <div class="action-buttons">
                        <a href="{{ route('admin.testimonials.edit', $testimonial->id) }}" class="btn-primary">
                            ✏️ Edit Testimoni
                        </a>
                        <a href="{{ route('admin.testimonials.index') }}" class="btn-secondary">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
@endsection