<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Seu Plano Está Expirando - DeepEyes</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #0a0a0f;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #0a0a0f; padding: 40px 20px;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%); border-radius: 16px; overflow: hidden; box-shadow: 0 20px 60px rgba(0,0,0,0.5);">
                    <!-- Header -->
                    <tr>
                        <td style="padding: 40px 40px 20px; text-align: center; border-bottom: 1px solid rgba(245, 158, 11, 0.2);">
                            <h1 style="margin: 0; font-size: 32px; font-weight: 700; background: linear-gradient(135deg, #8b5cf6, #06b6d4); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                                 DeepEyes
                            </h1>
                        </td>
                    </tr>
                    
                    <!-- Alert Banner -->
                    <tr>
                        <td style="padding: 0;">
                            <div style="background: linear-gradient(135deg, rgba(245, 158, 11, 0.2), rgba(234, 88, 12, 0.2)); padding: 20px 40px; border-left: 4px solid #f59e0b;">
                                <h2 style="margin: 0; color: #fbbf24; font-size: 20px; font-weight: 600;">
                                    ⏰ Seu plano expira em {{ $daysRemaining }} {{ $daysRemaining == 1 ? 'dia' : 'dias' }}!
                                </h2>
                            </div>
                        </td>
                    </tr>
                    
                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px;">
                            <p style="margin: 0 0 20px; color: #f1f5f9; font-size: 18px; font-weight: 500;">
                                Olá, {{ $user->name }}
                            </p>
                            
                            <p style="margin: 0 0 20px; color: #cbd5e1; font-size: 16px; line-height: 1.6;">
                                Seu plano <strong style="color: #8b5cf6;">{{ $user->plan?->name ?? 'atual' }}</strong> está prestes a expirar. Renove agora para continuar aproveitando todos os recursos da plataforma.
                            </p>
                            
                            <div style="background: rgba(139, 92, 246, 0.1); border: 1px solid rgba(139, 92, 246, 0.3); border-radius: 12px; padding: 20px; margin: 30px 0;">
                                <h3 style="margin: 0 0 15px; color: #8b5cf6; font-size: 16px; font-weight: 600;">
                                    🚀 Não perca acesso a:
                                </h3>
                                <ul style="margin: 0; padding: 0 0 0 20px; color: #cbd5e1; font-size: 14px; line-height: 2;">
                                    <li>Chat com IA especializada</li>
                                    <li>Terminal integrado</li>
                                    <li>Scanner de vulnerabilidades</li>
                                    <li>Checklists OWASP</li>
                                    <li>Geração de relatórios</li>
                                </ul>
                            </div>
                            
                            <div style="text-align: center; margin: 30px 0;">
                                <a href="https://wa.me/5511940968290?text=Olá! Quero renovar meu plano {{ $user->plan?->name ?? '' }} no DeepEyes." style="display: inline-block; padding: 14px 40px; background: linear-gradient(135deg, #22c55e, #16a34a); color: #ffffff; text-decoration: none; border-radius: 8px; font-weight: 600; font-size: 16px;">
                                    💬 Renovar via WhatsApp
                                </a>
                            </div>
                            
                            <p style="margin: 30px 0 0; color: #64748b; font-size: 14px; line-height: 1.6; text-align: center;">
                                Ou acesse nossos planos em: 
                                <a href="https://deepeyes.online/#pricing" style="color: #8b5cf6; text-decoration: none;">deepeyes.online/#pricing</a>
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="padding: 20px 40px; background: rgba(0,0,0,0.2); text-align: center; border-top: 1px solid rgba(139, 92, 246, 0.2);">
                            <p style="margin: 0; color: #64748b; font-size: 12px;">
                                © {{ date('Y') }} DeepEyes. Todos os direitos reservados.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
