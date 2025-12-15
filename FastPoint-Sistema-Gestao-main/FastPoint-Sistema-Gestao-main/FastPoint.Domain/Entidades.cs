using System;
using System.Collections.Generic;

namespace FastPoint.Domain
{
    // Entidade de Produto (Estoque)
    public class Produto
    {
        public int Id { get; private set; }
        public string Nome { get; private set; }
        public decimal Preco { get; private set; }
        public int QuantidadeEstoque { get; private set; }
        public bool Ativo { get; private set; }

        // Construtor para o EF Core
        protected Produto() { }

        public Produto(string nome, decimal preco, int quantidade)
        {
            // Validações simples de domínio (Clean Architecture)
            if (string.IsNullOrEmpty(nome)) throw new Exception("Nome é obrigatório");
            if (preco <= 0) throw new Exception("Preço deve ser maior que zero");
            if (quantidade < 0) throw new Exception("Estoque não pode ser negativo");

            Nome = nome;
            Preco = preco;
            QuantidadeEstoque = quantidade;
            Ativo = true;
        }

        public void DebitarEstoque(int quantidade)
        {
            if (QuantidadeEstoque < quantidade)
                throw new Exception($"Estoque insuficiente. Disponível: {QuantidadeEstoque}");
            
            QuantidadeEstoque -= quantidade;
        }
    }

    // Entidade de Usuário (Para Login)
    public class Usuario
    {
        public int Id { get; set; }
        public string Nome { get; set; }
        public string Email { get; set; }
        public string Senha { get; set; } // Em produção, usaríamos hash!
        public string Perfil { get; set; } // "Gerente" ou "Atendente"
    }

    // Entidade de Pedido
    public class Pedido
    {
        public int Id { get; set; }
        public DateTime Data { get; set; } = DateTime.UtcNow;
        public string Status { get; set; } = "Recebido"; // Recebido, Em Preparo, Pronto
        public string NomeCliente { get; set; }
        public List<PedidoItem> Itens { get; set; } = new List<PedidoItem>();
        public decimal ValorTotal { get; private set; }

        public void AdicionarItem(Produto produto, int quantidade)
        {
            produto.DebitarEstoque(quantidade); // Já valida o estoque aqui
            var item = new PedidoItem { ProdutoId = produto.Id, Quantidade = quantidade, PrecoUnitario = produto.Preco };
            Itens.Add(item);
            ValorTotal += quantidade * produto.Preco;
        }
    }

    public class PedidoItem
    {
        public int Id { get; set; }
        public int ProdutoId { get; set; }
        public Produto Produto { get; set; }
        public int Quantidade { get; set; }
        public decimal PrecoUnitario { get; set; }
    }
}