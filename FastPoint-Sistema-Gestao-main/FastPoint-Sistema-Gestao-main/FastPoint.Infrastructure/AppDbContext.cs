using Microsoft.EntityFrameworkCore;
using FastPoint.Domain;

namespace FastPoint.Infrastructure
{
    public class AppDbContext : DbContext
    {
        public AppDbContext(DbContextOptions<AppDbContext> options) : base(options)
        {
        }

        // Estas linhas virarão tabelas no seu banco PostgreSQL
        public DbSet<Produto> Produtos { get; set; }
        public DbSet<Usuario> Usuarios { get; set; }
        public DbSet<Pedido> Pedidos { get; set; }
        public DbSet<PedidoItem> PedidoItens { get; set; }

        protected override void OnModelCreating(ModelBuilder modelBuilder)
        {
            // Configurações extras (opcionais, mas boas para garantir integridade)
            modelBuilder.Entity<Produto>().HasKey(p => p.Id);
            modelBuilder.Entity<Pedido>().HasKey(p => p.Id);
            
            // Garante que o preço tenha precisão correta no banco
            modelBuilder.Entity<Produto>()
                .Property(p => p.Preco)
                .HasColumnType("decimal(18,2)");

             modelBuilder.Entity<PedidoItem>()
                .Property(p => p.PrecoUnitario)
                .HasColumnType("decimal(18,2)");
        }
    }
}