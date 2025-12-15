using Xunit;
using FastPoint.Domain;
using System;

namespace FastPoint.Tests
{
    public class TestesDeDominio
    {
        // TESTE 1: Verificar se cria produto válido corretamente
        [Fact]
        public void CriarProduto_ComDadosValidos_DeveFuncionar()
        {
            // Arrange & Act
            var produto = new Produto("X-Burguer", 20.00m, 10);

            // Assert
            Assert.Equal("X-Burguer", produto.Nome);
            Assert.Equal(20.00m, produto.Preco);
        }

        // TESTE 2: Garantir que não aceita preço negativo (Regra de Negócio)
        [Fact]
        public void CriarProduto_ComPrecoNegativo_DeveDarErro()
        {
            // Act & Assert
            Assert.Throws<Exception>(() => new Produto("X-Burguer", -5.00m, 10));
        }

        // TESTE 3: Garantir que nome é obrigatório
        [Fact]
        public void CriarProduto_SemNome_DeveDarErro()
        {
            // Act & Assert
            Assert.Throws<Exception>(() => new Produto("", 20.00m, 10));
        }

        // TESTE 4: Testar débito de estoque com sucesso
        [Fact]
        public void DebitarEstoque_QuantidadeSuficiente_DeveAtualizarEstoque()
        {
            // Arrange
            var produto = new Produto("Coca-Cola", 5.00m, 10);

            // Act
            produto.DebitarEstoque(3);

            // Assert
            Assert.Equal(7, produto.QuantidadeEstoque);
        }

        // TESTE 5: Testar erro ao vender mais do que tem no estoque
        [Fact]
        public void DebitarEstoque_QuantidadeInsuficiente_DeveDarErro()
        {
            // Arrange
            var produto = new Produto("Coca-Cola", 5.00m, 2);

            // Act & Assert
            // Tenta tirar 5 de onde só tem 2
            Assert.Throws<Exception>(() => produto.DebitarEstoque(5));
        }
    }
}