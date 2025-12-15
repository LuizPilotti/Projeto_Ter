using Microsoft.AspNetCore.Mvc;
using Microsoft.EntityFrameworkCore;
using FastPoint.Infrastructure;
using FastPoint.Domain;
using Microsoft.IdentityModel.Tokens;
using System.IdentityModel.Tokens.Jwt;
using System.Security.Claims;
using System.Text;

namespace FastPoint.API.Controllers
{
    [Route("api/[controller]")]
    [ApiController]
    public class UsuariosController : ControllerBase
    {
        private readonly AppDbContext _context;
        private readonly IConfiguration _configuration;

        public UsuariosController(AppDbContext context, IConfiguration configuration)
        {
            _context = context;
            _configuration = configuration;
        }

        // 1. REGISTRAR (Cria um usuário novo)
        [HttpPost("registrar")]
        public async Task<IActionResult> Registrar(Usuario usuario)
        {
            // Validação simples
            if (await _context.Usuarios.AnyAsync(u => u.Email == usuario.Email))
                return BadRequest("E-mail já cadastrado.");

            // Em um sistema real, aqui encriptaríamos a senha antes de salvar!
            _context.Usuarios.Add(usuario);
            await _context.SaveChangesAsync();

            return Ok(new { message = "Usuário criado com sucesso!" });
        }

        // 2. LOGIN (Gera o Token)
        [HttpPost("login")]
        public async Task<IActionResult> Login(string email, string senha)
        {
            // Busca usuário no banco
            var usuario = await _context.Usuarios
                .FirstOrDefaultAsync(u => u.Email == email && u.Senha == senha);

            if (usuario == null)
                return Unauthorized("Usuário ou senha inválidos.");

            // Se achou, GERA O TOKEN
            var token = GerarToken(usuario);
            return Ok(new { token = token, usuario = usuario.Nome, perfil = usuario.Perfil });
        }

        // Método auxiliar para criar o token JWT
        private string GerarToken(Usuario usuario)
        {
            var key = Encoding.ASCII.GetBytes(_configuration["Jwt:Key"]);
            var tokenHandler = new JwtSecurityTokenHandler();
            
            var tokenDescriptor = new SecurityTokenDescriptor
            {
                Subject = new ClaimsIdentity(new[]
                {
                    new Claim(ClaimTypes.Name, usuario.Nome),
                    new Claim(ClaimTypes.Role, usuario.Perfil) // Define se é Gerente ou Atendente
                }),
                Expires = DateTime.UtcNow.AddHours(2), // Token vale por 2 horas
                SigningCredentials = new SigningCredentials(new SymmetricSecurityKey(key), SecurityAlgorithms.HmacSha256Signature)
            };

            var token = tokenHandler.CreateToken(tokenDescriptor);
            return tokenHandler.WriteToken(token);
        }
    }
}